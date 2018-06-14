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
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CommerceBundle\Form\Type\GuestOrderLookupFormType;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * OrderController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderController extends Controller
{
    /**
     * @Template("SplicedCommerceBundle:Order:view.html.twig")
     * @Route("/account/order/{orderNumber}", name="account_order_view")
     * @Secure(roles="ROLE_USER")
     *
     * View Action
     *
     */
    public function viewAction($orderNumber)
    {
         try {

             $order = $this->getDoctrine()
               ->getManager()
               ->getRepository("SplicedCommerceBundle:Order")
               ->findOneByOrderNumberForCustomer(
                   $orderNumber, 
                   $this->get('security.context')->getToken()->getUser()
               );

         } catch (NoResultException $e) { 
             throw $this->createNotFoundException('Order Not Found');
         }

         return $this->renderOrderView($order);
    }

    /**
     * @Template("SplicedCommerceBundle:Order:printer_friendly.html.twig")
     * @Route("/account/order/{orderNumber}/printer-friendly", name="account_order_view_printer_friendly")
     * @Secure(roles="ROLE_USER")
     *
     * viewPrinterFriendlyAction
     *
     */
    public function viewPrinterFriendlyAction($orderNumber)
    {
        try {

            $order = $this->getDoctrine()->getManager()->getRepository("SplicedCommerceBundle:Order")
            ->findOneByOrderNumberForCustomer($orderNumber, $this->get('security.context')->getToken()->getUser());

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Order Not Found');
        }

        $paymentMethod = $this->get('commerce.payment_manager')->getProvider($order->getPayment()->getPaymentMethod());
        $shippingProvider = $this->get('commerce.shipping_manager')->getProvider($order->getShipment()->getShipmentProvider());
        $shippingMethod = $shippingProvider->getMethod($order->getShipment()->getShipmentMethod());

        $viewVars = array(
            'order' => $order,
            'paymentMethod' => $paymentMethod,
            'shippingProvider' => $shippingProvider,
            'shippingMethod' => $shippingMethod,
        );

        if ($this->getRequest()->query->has('format') && strtolower($this->getRequest()->query->get('format')) == 'pdf') {
            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($this->renderView('SplicedCommerceBundle:Order:printer_friendly.html.twig', array_merge($viewVars,array('pdf' => true)))),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => sprintf('attachment; filename="Order-Number-%s.pdf"', $order->getOrderNumber())
                )
            );
        }

        return $viewVars;
    }

    /**
     * @Template("SplicedCommerceBundle:Order:printer_friendly.html.twig")
     * @Route("/order/track/{orderNumber}/printer-friendly", name="commerce_guest_order_view_printer_friendly")
     *
     * viewPrinterFriendlyAction
     *
     */
    public function guestViewPrinterFriendlyAction($orderNumber)
    {
        try {
        
            $order = $this->getDoctrine()->getManager()->getRepository("SplicedCommerceBundle:Order")
            ->findOneByOrderNumber($orderNumber);
            
            if($order->getProtectCode() != $this->getRequest()->query->get('c')){
                throw $this->createNotFoundException('Order Not Found');
            }
            
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Order Not Found');
        }
        
        $paymentMethod = $this->get('commerce.payment_manager')->getProvider($order->getPayment()->getPaymentMethod());
        $shippingProvider = $this->get('commerce.shipping_manager')->getProvider($order->getShipment()->getShipmentProvider());
        $shippingMethod = $shippingProvider->getMethod($order->getShipment()->getShipmentMethod());
        
        $viewVars = array(
            'order' => $order,
            'paymentMethod' => $paymentMethod,
            'shippingProvider' => $shippingProvider,
            'shippingMethod' => $shippingMethod,
        );
        
        if ($this->getRequest()->query->has('format') && strtolower($this->getRequest()->query->get('format')) == 'pdf') {
            return new Response(
                    $this->get('knp_snappy.pdf')->getOutputFromHtml($this->renderView('SplicedCommerceBundle:Order:printer_friendly.html.twig', array_merge($viewVars,array('pdf' => true)))),
                    200,
                    array(
                            'Content-Type'          => 'application/pdf',
                            'Content-Disposition'   => sprintf('attachment; filename="Order-Number-%s.pdf"', $order->getOrderNumber())
                    )
            );
        }
        
        return $viewVars;
    }
    
    /**
     * @Route("/orders/last-order-printer-friendly", name="order_view_printer_friendly_last_order")
     * @Template("SplicedCommerceBundle:Order:printer_friendly.html.twig")
     * 
     * viewPrinterFriendlyLastSuccessfulOrderAction
     *
     */
    public function viewPrinterFriendlyLastSuccessfulOrderAction()
    {
        try {

            $order = $this->getDoctrine()->getManager()->getRepository("SplicedCommerceBundle:Order")
            ->findOneById($this->get('commerce.checkout_manager')->getLastCompletedOrder());
        
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Order Not Found');
        }
        
        $paymentMethod = $this->get('commerce.payment_manager')->getProvider($order->getPayment()->getPaymentMethod());
        $shippingProvider = $this->get('commerce.shipping_manager')->getProvider($order->getShipment()->getShipmentProvider());
        $shippingMethod = $shippingProvider->getMethod($order->getShipment()->getShipmentMethod());
        
        $viewVars = array(
            'order' => $order,
            'paymentMethod' => $paymentMethod,
            'shippingProvider' => $shippingProvider,
            'shippingMethod' => $shippingMethod,
        );
        

        if ($this->getRequest()->query->has('format') && strtolower($this->getRequest()->query->get('format')) == 'pdf') {
            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($this->renderView('SplicedCommerceBundle:Order:printer_friendly.html.twig', array_merge($viewVars,array('pdf' => true)))),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => sprintf('attachment; filename="Order-Number-%s.pdf"', $order->getOrderNumber())
                )
            );
        }   
        
        return $viewVars;
    }
    
    /**
     * @Template("SplicedCommerceBundle:Order:guest_order_lookup.html.twig")
     * @Route("/orders/track", name="commerce_guest_order_lookup")
     *
     * guestOrderLookupAction
     *
     */
    public function guestOrderLookupAction()
    {
        $request = $this->getRequest();
        $session = $this->get('session');
        
        $formType = new GuestOrderLookupFormType(GuestOrderLookupFormType::LOOKUP_TYPE_ORDER_NUMBER_EMAIL);
        $form = $this->createForm($formType);
        
        $formTypeAlt = new GuestOrderLookupFormType(GuestOrderLookupFormType::LOOKUP_TYPE_EMAIL_BILLING_ZIPCODE);
        $formAlt = $this->createForm($formTypeAlt);
        
        if($request->getMethod() == 'POST'){
            
            if($request->request->has($formType->getName()) && $form->bind($request)){
                if($form->isValid()){
                    $formData = $form->getData();
                    try{
                        $order = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("SplicedCommerceBundle:Order")
                        ->findOneByOrderNumberAndEmail($formData['orderNumber'], $formData['email']);
                        
                        return $this->renderOrderView($order);
                        
                    }catch(NoResultException $e){
                        $session->getFlashBag()->add('error', 'Order not found.');
                    }
                } else {
                    $session->getFlashBag()->add('error', 'There is an error with your input. Please review and try again.');
                }
                
                
            } else if($request->request->has($formTypeAlt->getName()) && $formAlt->bind($request)){
                if($formAlt->isValid()){
                    $formData = $form->getData();
                    try{
                        $order = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("SplicedCommerceBundle:Order")
                        ->findOneByEmailAndBillingZipcode($formData['email'], $formData['billingZipcode']);
                        
                        return $this->renderOrderView($order);
                        
                    }catch(NoResultException $e){
                        $session->getFlashBag()->add('error', 'Order not found.');
                    }
                } else {
                    $session->getFlashBag()->add('error', 'There is an error with your input. Please review and try again.');
                }
            }
            
        }



        $this->get('commerce.breadcrumb')->createBreadcrumb('Order Lookup', 'Order Lookup', $this->generateUrl('commerce_guest_order_lookup'), 1, true);


        return array(
            'form' => $form->createView(),
            'formAlt' => $formAlt->createView(),   
        );
    }

    /**
     * 
     */
    protected function renderOrderView(OrderInterface $order)
    {
        $paymentMethod = $this->get('commerce.payment_manager')
        ->getProvider($order->getPayment()->getPaymentMethod());
         
        $shippingProvider = $this->get('commerce.shipping_manager')
        ->getProvider($order->getShipment()->getShipmentProvider());
         
        $shippingMethod = $shippingProvider->getMethod($order->getShipment()->getShipmentMethod());
        
        
        return $this->render('SplicedCommerceBundle:Order:view.html.twig',array(
            'order' => $order,
            'paymentMethod' => $paymentMethod,
            'shippingProvider' => $shippingProvider,
            'shippingMethod' => $shippingMethod,
        ));
    }
}
