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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormInterface;
use Spliced\Bundle\CommerceBundle\Entity;
use Spliced\Component\Commerce\Model;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Bundle\CommerceBundle\Form\Type as Forms;
use Spliced\Component\Commerce\Shipping\ShippingAddress;
use Spliced\Component\Commerce\Shipping\Model\ConfigurableShippingMethod;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Payment\PaymentErrorException;
use Spliced\Component\Commerce\Payment\PaymentDeclinedException;
use Doctrine\ORM\NoResultException;

/**
 * CheckoutController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutController extends Controller
{


    /**
     * @Template("SplicedCommerceBundle:Checkout:index.html.twig")
     * @Route("/checkout", name="commerce_checkout")
     */
    public function indexAction()
    {

        $cartManager = $this->get('commerce.cart');

        if (!$cartManager->getItems()->count()) {
            return $this->redirect($this->generateUrl('commerce_cart'));
        }

        $configurationManager   = $this->get('commerce.configuration');
        $orderManager           = $this->get('commerce.order_manager');
        $customer               = $this->get('security.context')->getToken()->getUser();
        $securityContext        = $this->get('security.context');
        $checkoutManager        = $this->get('commerce.checkout_manager');
        $breadcrumbs            = $this->get('commerce.breadcrumb');
        $dispatcher             = $this->get('event_dispatcher');
        $request                = $this->get('request');
        $session                = $this->get('session');
        $isXmlHttpRequest       = $request->isXmlHttpRequest();
        $productAttributerUserFormBuilder = $this->get('commerce.product.attribute_option_user_data_form_builder');
        
        $cart = $cartManager->getCart();
        
        // first we check to see that all information required is collected from products
        // with user data collection attributes
        $userDataForms = $productAttributerUserFormBuilder->buildForms(array(
             // just to validate the already submitted data if we have it 
            'csrf_protection' => false, 
        ));
        
        $hasUserDataValidationError = false;
        
        foreach($userDataForms as $itemId => &$userDataForm){
            if(is_null($userDataForm)){
                continue;
            }
            
            $itemData = $cart->getItemById($itemId)->getItemData();
            
            $userDataForm->bind(isset($itemData['user_data']) ? $itemData['user_data'] : array());
            
            if(!$userDataForm->isValid()){
                //print_r($userDataForm->getData());
                //die($this->render('SplicedCommerceBundle:Common:debug_form.html.twig', array('form' => $userDataForm->createView()))->getContent());
                $hasUserDataValidationError = true;
            }
        }
        
        if($hasUserDataValidationError){
            if($isXmlHttpRequest){
                // TODO display a modal with options to continue
            }
            // render the cart page to show errors 
            // and collect information
            $session->getFlashBag()->add('error', 'One or more items in your shopping cart require additional information to complete your order.');
            return $this->render('SplicedCommerceBundle:Cart:index.html.twig', array(
                'cartContents' => $cart->getItems(),
                'userDataForms' => array_map(function($userForm){
                    if($userForm instanceof FormInterface){
                        return $userForm->createView();
                    }
                    return $userForm;
                }, $userDataForms),
            ));
        }
        
        // add breadcumbs
        $breadcrumbs->createBreadcrumb(
            'Checkout', 
            'Checkout', 
            $this->generateUrl('commerce_checkout'),
            null, 
            true
        )->createBreadcrumb(
            'Step '.$checkoutManager->getCurrentStep(), 
            'Step '.$checkoutManager->getCurrentStep(),
           '#',
           null,
           true
        );

        $order = $orderManager->getOrder();
        

        if(!$order instanceof OrderInterface){
            $order = $orderManager->createOrder($this->getRequest());
            
            $dispatcher->dispatch(
                Events\Event::EVENT_CHECKOUT_START, 
                new Events\CheckoutStartEvent($order)
            );
        }

        // handle going back to previous steps
        // making sure progress does not exceed what
        // the user has already completed
        if ($request->query->has('step')) {
            $backToStep = (int) $request->query->get('step');
            $previousStep = $checkoutManager->getCurrentStep();
            if ($backToStep <= $checkoutManager->getLastCompletedStep()) {
                $checkoutManager->setCurrentStep($backToStep);
            }
        }

        $form = $checkoutManager->getCheckoutForm($order);
        
        // process this step
        $processStepEvent = $dispatcher->dispatch(
            Events\Event::EVENT_CHECKOUT_PROCESS_STEP,
            new Events\CheckoutProcessStepEvent($form, $request)
        );
                
        if($processStepEvent->getResponse() instanceof Response) {
            return $processStepEvent->getResponse();
        }
        
        if($processStepEvent->isComplete()) {
            
            // we have completed the checkout process?
            // if so we finalize the order by notifying
            // the event dispatcher to complete the processing
            // of the order and authorize payment if required
            if($processStepEvent->isLastStep()) {
                $finalzeCheckoutEvent = $dispatcher->dispatch(
                    Events\Event::EVENT_CHECKOUT_FINALIZE,
                    new Events\CheckoutFinalizeEvent($processStepEvent->getOrder(), $request)
                );
                
                if($finalzeCheckoutEvent->getResponse() instanceof Response) {
                    return $finalzeCheckoutEvent->getResponse();
                }
                    
                if($finalzeCheckoutEvent->isComplete()){
                    return $this->redirect($this->generateUrl('commerce_checkout_success'));
                }
            }
            
            // lets redirect back to the checkout page
            // clearing any post data and moving on to the 
            // next step    
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/checkout/success", name="commerce_checkout_success")
     * @Template("SplicedCommerceBundle:Checkout:success.html.twig")
     */
    public function successAction()
    {
        $checkoutManager = $this->get('commerce.checkout_manager');
        $dispatcher           = $this->get('event_dispatcher');

        $debugLast = $this->getRequest()->query->has('debug');
        
        if ($checkoutManager->getCurrentStep() != $checkoutManager->getLastStep() || ! $checkoutManager->hasCurrentOrder()) {
           if(!$debugLast||!$this->get('kernel')->getEnvironment() == 'dev'){
               return $this->redirect($this->generateUrl('commerce_checkout'));
           }
        }
        
        try {

            $order = $this->getDoctrine()->getRepository('SplicedCommerceBundle:Order')
                ->findOneById($debugLast ? $checkoutManager->getLastCompletedOrder() : $checkoutManager->getCurrentOrderId());
                        
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }

        $dispatcher->dispatch(
            Events\Event::EVENT_CHECKOUT_SUCCESS,
            new Events\CheckoutCompleteEvent($order, $this->getRequest())
        );

        return array('order' => $order);
    }

    /**
     * createOrder
     *
     * @param Model\OrderInterface $order
     *
     * @return Model\OrderInterface
     */
    protected function createOrder(Model\OrderInterface $order = null)
    {
        if (is_null($order)) {
            $order = $this->get('commerce.configuration')
             ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER);
        }
        
        $order->setStartIp($this->get('request')->getClientIp());
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            $order->setCustomer($this->get('security.context')->getToken()->getUser());
        }
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        
        
        return $order;
    }
    
    /**
     * @Route("/checkout/cancel", name="checkout_cancel")
     * 
     * Cancels the current order and all the data user has
     * supplied so far
     */
    public function cancelCheckoutAction()
    {
            
        try {
            $order = $this->getDoctrine()->getRepository('SplicedCommerceBundle:Order')
                ->findOneById($this->get('commerce.checkout_manager')->getCurrentOrderId());
            
            $order->setOrderStatus(Model\OrderInterface::STATUS_ABANDONED);
            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();
            
        } catch(NoResultException $e) { /* do nothing */ }
                
        
        
        $this->get('commerce.checkout_manager')->reset();

        $this->get('session')->getFlashBag()->add('success', 'Order has been cancelled and checkout progress has been cleared.');
        
        return $this->redirect($this->generateUrl('cart'));
    }
    
    /**
     * @Route("/checkout/reset", name="checkout_reset")
     * For Debugging Purposes
     */
    public function resetCheckoutAction()
    {
        if ($this->get('kernel')->getEnvironment() == 'dev') { //only in dev
            $this->get('commerce.checkout_manager')->reset();
        }

        return $this->redirect($this->generateUrl('commerce_checkout'));
    }
        
    /**
     * @Route("/checkout/remote/{provider}/cancel/{order}", name="commerce_checkout_remote_cancel")
     */
    public function remotelyProcessedCancelAction($provider, $order)
    {
        $cartManager      = $this->get('commerce.cart');
        $customer          = $this->get('security.context')->getToken()->getUser();
        $checkoutManager = $this->get('commerce.checkout_manager');
        $dispatcher      = $this->get('event_dispatcher');
        $request         = $this->getRequest();
        
        try{
            $paymentProvider = $checkoutManager->getPaymentManager()->getProvider($provider);
        } catch(\Exception $e) {
            // TODO: handle notification of error or log of error
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }
        
        if ($checkoutManager->hasCurrentOrder()) {
            try {
        
                $order = $this->getDoctrine()->getRepository('SplicedCommerceBundle:Order')
                ->findOneById($checkoutManager->getCurrentOrderId());
        
            } catch (NoResultException $e) {
                $this->getLogger()->error(sprintf('Order %s | Remotely Processed Payment Cancel Order Not Found', $checkoutManager->getCurrentOrderId()));
                return $this->redirect($this->generateUrl('commerce_checkout'));
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'You currently do not have any orders pending to process.');
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }
        
        $update = $paymentProvider->cancelRemotePayment($order, $request);

        return $update instanceof Response ? $update : $this->redirect($this->generateUrl('commerce_checkout', array('step' => CheckoutManager::STEP_PAYMENT)));
    }
    

    /**
     * @Route("/checkout/remote/{provider}/update/{order}", name="commerce_checkout_remote_update")
     */
    public function remotelyProcessedUpdateAction($provider, $order)
    {
        die('TODO');
        // TODO
    }
    
    /**
     * @Route("/checkout/remote/{provider}/complete/{order}", name="commerce_checkout_remote_complete")
     */
    public function remotelyProcessedCompleteAction($provider, $order)
    {
        $cartManager      = $this->get('commerce.cart');
        $orderManager     = $this->get('commerce.order_manager');
        $customer         = $this->get('security.context')->getToken()->getUser();
        $checkoutManager  = $this->get('commerce.checkout_manager');
        $dispatcher       = $this->get('event_dispatcher');
        $request          = $this->getRequest();
                
        try{
            $paymentProvider = $checkoutManager->getPaymentManager()->getProvider($provider);
        } catch(\Exception $e) {
            // TODO: handle notification of error or log of error
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }
        
        if (!$checkoutManager->hasCurrentOrder() || ! $order = $orderManager->getOrder()) {
            $this->get('session')->getFlashBag()->add('error', 'You currently do not have any orders pending to process.');
            return $this->redirect($this->generateUrl('commerce_checkout'));
        }
        
        $paymentProvider->completeRemotePayment($order, $request);
        
        $order = $checkoutManager->generateOrderNumber($order);
        
        $dispatcher->dispatch(
            Events\Event::EVENT_CHECKOUT_COMPLETE,
            new Events\CheckoutCompleteEvent($order, $request)
        ); 
        
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'redirect' => $this->generateUrl('commerce_checkout_success'),
            ));
        }        
        return $this->redirect($this->generateUrl('commerce_checkout_success'));
        
    }

    /**
     * getLogger
     * 
     * @return Logger
     */
     protected function getLogger(){
         return $this->get('commerce.logger');
     }
     
     /**
      * @Route("/checkout/get-shipping-methods", name="commerce_checkout_get_shipping_methods")
      * @Method("POST")
      */
     public function getShippingMethodsRequestAction()
     {
     	$request = $this->get('request');
     	
     	if (!$request->isXmlHttpRequest()) {
     		throw $this->createNotFoundException();
     	}
     	
     	$address = new ShippingAddress($request->request->get('address', array()));
     	
     	$methods = $this->get('commerce.shipping_manager')->getAvailableMethodsForDesination($address);
     	
     	
     	return new JsonResponse(array(
     		'success' => true,
     		'methods' => $methods->toArray(),
     		'content' => $this->render('SplicedCommerceBundle:Checkout/Form:shipping_methods_ajax.html.twig', array(
     			'methods' => $methods
     		))->getContent(),
     	));
     }
}
