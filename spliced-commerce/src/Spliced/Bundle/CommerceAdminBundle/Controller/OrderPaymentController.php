<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\OrderPaymentMemoType;
use Spliced\Component\Commerce\HttpFoundation\AjaxResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * OrderPayment controller.
 *
 * @Route("/order/payment")
 */
class OrderPaymentController extends Controller
{
    /**
     * @Route("/add-memo/{paymentId}", name="commerce_admin_order_payment_add_memo")
     * @Method({"POST","GET"})
     */
    public function addMemoAction($paymentId)
    {
        try{
            $order = $this->getDoctrine()->getManager()
            ->getRepository('SplicedCommerceAdminBundle:Order')
            ->findOneByPaymentId($paymentId);
                
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Payment Not Found');
        }
        
        $formType = new OrderPaymentMemoType();
        $form = $this->createForm($formType);
        
        if($this->getRequest()->getMethod() == 'POST') {
            if($this->getRequest()->request->has($formType->getName()) && $form->bind($this->getRequest()) && $form->isValid()){
                $memo = $form->getData();
                
                $memo->setPreviousStatus($order->getPayment()->getPaymentStatus());
                
                $this->get('event_dispatcher')->dispatch(
                    Events\Event::EVENT_ORDER_PAYMENT_UPDATE,
                    new Events\OrderPaymentUpdateEvent($order, $memo)
                );
                
                $this->get('session')->getFlashBag()->add('success', 'Payment Memo Successfully Added');
                
                return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
        
            } else if(!$this->getRequest()->isXmlHttpRequest()) {
                $this->get('session')->getFlashBag()->add('error', 'Could not validate your input');
            }
        }
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new AjaxResponse(array(
                'success' => true,
                'message' => 'Add Payment Memo',
                'modal' => $this->render('SplicedCommerceAdminBundle:OrderPayment:add_memo_modal.html.twig',array(
                    'order' => $order,
                    'form' => $form->createView(),
                ))->getContent(),
            ));
        }
        
        return array(
            'order' => $order,
            'form' => $form->createView(),
        );
    }
}
