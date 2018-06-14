<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductBundledItemController
 *
 * @Route("/product/{productId}/bundled-item")
 */
class ProductBundledItemController extends Controller
{
    
    /**
     * @Route("/delete/{bundledItemId}", name="commerce_admin_product_bundled_item_delete")
     * @Method({"GET","POST"})
     * @Template("SplicedCommerceAdminBundle:ProductBundledItem:add.html.twig")
     */
    public function deleteAction($productId, $bundledItemId)
    {
        $product = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
        
        if(!$product){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'product_id' => $productId,
                    'image_id' => $imageId,
                ));
            }
            throw $this->createNotFoundException('Product Not Found');
        }
 
        $bundledItem = null;
        foreach($product->getBundledItems() as $_bundledItem){
            if ($_bundledItem->getId() == $bundledItemId) {
                $bundledItem = $_bundledItem;
            }
        }
        
        if(!$bundledItem){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Bundled Item Not Found',
                    'product_id' => $product->getId(),
                    'bundled_item_id' => $bundledItemId,
                ));
            }
            throw $this->createNotFoundException('Product Bundled Item Not Found');
        }

        $product->removeBundledItem($bundledItem);
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'product_id' => $product->getId(),
                'bundled_item_id' => $bundledItem->getId(),
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Bundled Item Successfully Deleted');
         
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
}
