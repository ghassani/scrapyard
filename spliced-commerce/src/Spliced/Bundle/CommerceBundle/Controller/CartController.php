<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Doctrine\ORM\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CommerceBundle\Form\Type\CartShippingQuoteFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Cart\CartItemCollection;
use Spliced\Component\Commerce\Cart\CartItem;
use Spliced\Component\Commerce\Event;
use Spliced\Component\Commerce\Model\ProductInterface;
use Symfony\Component\Form\FormInterface;

/**
 * CartController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartController extends Controller
{
    /**
     * @Template("SplicedCommerceBundle:Cart:index.html.twig")
     * @Route("/cart", name="commerce_cart")
     *
     */
    public function indexAction()
    {
        $cartManager = $this->get('commerce.cart');
        $checkoutManager = $this->get('commerce.checkout_manager');
        $cartContents = $cartManager->getItems();

        $this->get('commerce.breadcrumb')->createBreadcrumb(
            'Shopping Cart',
            'Shopping Cart',
            $this->generateUrl('commerce_cart'),
            null,
            true
        );

        $shippingQuoteForm = $this->createForm(new CartShippingQuoteFormType());
        
        /*$shippingQuoteFormData = $cartManager->getShippingDestinationArray();
        $shippingQuoteForm->setData($shippingQuoteFormData);

        if (isset($shippingQuoteFormData['country'])&&!empty($shippingQuoteFormData['country'])) {
            $shippingOptions = $this->get('commerce.shipping_manager')
              ->getAvailableMethodsForDesination($shippingQuoteFormData['country']);
        }*/

        if($this->getRequest()->isXmlHttpRequest()){
             return new JsonResponse(array(
                'success' => true,
                'message' => 'Shopping Cart',
                'content' => $this->render('SplicedCommerceBundle:Cart:index_ajax.html.twig',array(
                    'items' => $cartContents,
                ))->getContent(),
            ));
        }
        
  
        $userDataForms = $this->get('commerce.product.attribute_option_user_data_form_builder')->buildForms();
        
        return array(
            'cartContents'     => $cartContents,
            'shippingQuoteForm' => $shippingQuoteForm->createView(),
            'shippingOptions' => isset($shippingOptions) ? $shippingOptions : null,
            'userDataForms' => array_map(function(&$userForm){
                if(is_object($userForm)){ 
                    return $userForm->createView();
                }
                return null;
            }, $userDataForms),
        );
    }

    /**
     * @Route("/cart/update", name="commerce_cart_update")
     * @Method({"POST"})
     *
     */
    public function updateAction()
    {
        $cartManager     = $this->get('commerce.cart');
        $dispatcher        = $this->get('event_dispatcher');
        $hasUserDataValidationError = false;
        
        if (!$this->getRequest()->request->has('cart')) {
            $this->get('session')->getFlashBag()->add('error', 'Invalid Request Type');
            return $this->redirect($this->generateUrl('commerce_cart'));
        }
        
        $userDataForms = $this->get('commerce.product.attribute_option_user_data_form_builder')
          ->buildForms();

        foreach ($this->getRequest()->request->get('cart') as $itemId => $_item) {

            $product = $this->get('commerce.entity_manager')
              ->getRepository('SplicedCommerceBundle:Product')
              ->findOneById($_item['product']);
            
            $quantity = $_item['quantity'] < 0 ? 1 : (int) $_item['quantity'];
  
            $item = $cartManager->getCart()->getItemById($itemId);
            
            $itemData = $item->getItemData();
            
            if(is_null($itemData)){
                $itemData = array();
            }
            
            if($product->hasUserDataAttributes() && $quantity !== 0 ){
                $userDataForm = isset($userDataForms[$item->getId()]) ? $userDataForms[$item->getId()] : null;
                
                if($userDataForm){
                    
                    if($userDataForm->bind($this->getRequest()) && $userDataForm->isValid()){
                        $itemData['user_data'] = $userDataForm->getData();
                        
                    } else {
                        $this->get('session')->getFlashBag()->add('error', sprintf('%s Requires Some Additional Information', $product->getName()));
                        $hasUserDataValidationError = true;
                    }
                }
            }
            
            if(($quantity == 0 && !$item->isNonRemovable() && !$item->isBundled()) || (isset($_item['remove']) && $_item['remove'] == 1)){
                $cartManager->remove($item);
                continue;
            }
            
            $item->setItemData($itemData);
 
            $cartManager->update($item, $quantity); 
        }
        
        // handle shipping quote country/zipcode selection
        $shippingQuoteForm = $this->createForm(new CartShippingQuoteFormType());
        $shippingQuoteForm->bind($this->getRequest());

        $shippingData = $shippingQuoteForm->getData();

        $cartManager->setShippingDestination($shippingData['country'])
          ->setShippingDestinationZipcode($shippingData['zipcode']);

        if($hasUserDataValidationError){
            return $this->render('SplicedCommerceBundle:Cart:index.html.twig', array(
                'cartContents'     => $cartManager->getItems(),
                'shippingQuoteForm' => $shippingQuoteForm->createView(),
                'shippingOptions' => isset($shippingOptions) ? $shippingOptions : null,
                'userDataForms' => array_map(function($userForm){
                    if($userForm instanceof FormInterface){
                        return $userForm->createView();
                    }
                    return $userForm;
                }, $userDataForms),
            ));
        } 
        
        $dispatcher->dispatch(
            Event\Event::EVENT_CART_UPDATE, 
            new Event\CartUpdateEvent()
        );

        $this->get('session')->getFlashBag()->add('notice', 'Shopping Cart Updated');

        return $this->redirect($this->generateUrl('commerce_cart'));
    }

    /**
     * @Template("SplicedCommerceBundle:Cart:index.html.twig")
     * @Route("/cart/add", name="commerce_cart_add")
     * @Method({"POST","PUT","GET"})
     *
     */
    public function addAction()
    {
        $request     = $this->get('request');
        $cart        = $this->get('commerce.cart');
        $dispatcher  = $this->get('event_dispatcher');
        $productId   = $request->request->get('id', $request->query->get('id'));
        $quantity    = $request->request->get('quantity', $request->query->get('quantity', 1));
        
        if($quantity < 1){
            $quantity = 1; // set it to 1 in its 0 or less    
        }
        
        if (!$request->request->has('id') && !$request->query->has('id')) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Specified',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                        'title' => 'Whoops',
                        'body' => '<p>Product Not Specified.</p>'
                    ))->getContent()
                ));
            }
            throw $this->createNotFoundException("Product Not Specified");
        }

        $product = $this->get('commerce.entity_manager')
          ->getRepository('SplicedCommerceBundle:Product')
          ->findOneById($productId);

        if(!$product instanceof ProductInterface){
            
            $this->get('commerce.logger')->error(sprintf('Attempted to add non-existant or inactive product with ID %s',
                $request->request->get('id', $productId)
            ));
            
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                        'title' => 'Whoops',
                        'body' => '<p>Product was not found.</p>'
                    ))->getContent()
                ));
            }
            throw $this->createNotFoundException("Product Not Specified");
        }

        $success = $cart->add($product, $quantity, null, array());
        
        if(false === $success){
            
            $errorMessage = sprintf('An unexpected error occoured while adding product %s to cart', $product->getId());
            
            $this->get('commerce.logger')->error($errorMessage);
            
            if($request->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                        'title' => 'Whoops',
                        'body' => '<p>'.$errorMessage.'</p>'
                    ))->getContent()
                ));
            }
            
            $this->get('session')->getFlashBag()->add('error', $errorMessage);
            return $this->redirect($this->generateUrl('commerce_homepage'));
        }
        
        $dispatcher->dispatch(
            Event\Event::EVENT_CART_ITEM_ADD, 
            new Event\AddToCartEvent($product, $quantity)
        );

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Product Added To Cart',
                'content' => $this->render('SplicedCommerceBundle:Cart:add_ajax.html.twig',array(
                    'product' => $product,
                    'added' => $quantity,
                    'total' => $cart->getQuantity($product),
                    'items' => $cart->getItems(),
                ))->getContent(),
            ));
        } else {
            return $this->redirect($this->generateUrl('commerce_cart'));
        }
    }

    /**
     * @Template("SplicedCommerceBundle:Cart:index.html.twig")
     * @Route("/cart/remove/{itemId}", name="commerce_cart_remove")
     * @Method({"POST"})
     *
     */
    public function removeAction($itemId)
    {
        $request     = $this->get('request');
        $cartManager = $this->get('commerce.cart');
        $dispatcher    = $this->get('event_dispatcher');
        $session = $this->get('session');

        if(!$cartManager->getCart()){
            if($request->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'There is nothing in your shopping cart',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                            'title' => 'Shopping Cart Empty',
                            'body' => '<p>Your shopping cart is empty.</p>',
                    ))->getContent(),
                ));
            }

            return $this->redirect($this->generateUrl('commerce_cart'));
        }
        
        $cartItem = $cartManager->getCart()->getItemById($itemId);

        if(!$cartItem) {
            if($request->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'There is nothing in your shopping cart',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                        'title' => 'Item Could Not Found',
                        'body' => '<p>Item does not exist in your shopping cart.</p>',
                    ))->getContent(),
                ));
            }
             
            $session->getFlashBag()->add('error', 'Item does not exist in your shopping cart.');
            return $this->redirect($this->generateUrl('commerce_cart'));
        }
        
        if($cartItem->isChild() && $cartItem->isNonRemovable()){
                
            if($request->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => true,
                    'message' => 'There is nothing in your shopping cart',
                    'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                        'title' => 'Item Could Not Be Removed',
                        'body' => sprintf('<p>Product is required by %s and is not removable unless it is removed.</p>',
                            $cartItem->getParent()->getProduct()->getName()
                        ),
                    ))->getContent(),
                ));
            }
            
            $session->getFlashBag()->add('error', sprintf('Product is required by %s and is not removable unless it is removed.',
                $cartItem->getParent()->getProduct()->getName()
            ));
            return $this->redirect($this->generateUrl('commerce_cart'));
        }

        $cartManager->remove($cartItem);
                
        $dispatcher->dispatch(
            Event\Event::EVENT_CART_ITEM_REMOVE,
            new Event\RemoveFromCartEvent($cartItem->getProduct())
        );
                

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Product Removed From Cart',
                'redirect' => $this->generateUrl('commerce_cart'),
                'content' => $this->render('SplicedCommerceBundle:Common:modal.html.twig',array(
                    'title' => 'Successfully Removed Item',
                    'body' => '<p>Product has been removed from your shopping cart.</p>',
                ))->getContent(),
            ));
        }
        
        $session->getFlashBag()->add('success', 'Item removed from shopping cart.');
        return $this->redirect($this->generateUrl('commerce_cart'));
    }
}