<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Doctrine\ORM\NoResultException;

use Spliced\Bundle\CommerceAdminBundle\Form\Type\OrderShipmentMemoType;
use Spliced\Component\Commerce\HttpFoundation\AjaxResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * OrderShipment controller.
 *
 * @Route("/order/shipment")
 */
class OrderShipmentController extends Controller
{

    /**
     * @Route("/add-memo/{shipmentId}", name="commerce_admin_order_shipment_add_memo")
     * @Method({"POST","GET"})
     * @Template("SplicedCommerceAdminBundle:OrderShipment:add_memo.html.twig")
     */
    public function addMemoAction($shipmentId)
    {
        try{
            $order = $this->getDoctrine()->getManager()
              ->getRepository('SplicedCommerceAdminBundle:Order')
              ->findOneByShipmentId($shipmentId);
            
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Shipment Not Found');            
        }
        
        $formType = new OrderShipmentMemoType();
        $form = $this->createForm($formType);
        
        if($this->getRequest()->getMethod() == 'POST') {
            if($this->getRequest()->request->has($formType->getName()) && $form->bind($this->getRequest()) && $form->isValid()){
                $memo = $form->getData();
                
                $memo->setPreviousStatus($order->getShipment()->getShipmentStatus());
                
                $this->get('event_dispatcher')->dispatch(
                    Events\Event::EVENT_ORDER_SHIPMENT_UPDATE,
                    new Events\OrderShipmentUpdateEvent($order, $memo)
                );                
                $this->get('session')->getFlashBag()->add('success', 'Shipment Memo Successfully Added');
                return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
                
            } else if(!$this->getRequest()->isXmlHttpRequest()) {
                $this->get('session')->getFlashBag()->add('error', 'Could not validate your input');
            }
        }        

        if($this->getRequest()->isXmlHttpRequest()){
            return new AjaxResponse(array(
                'success' => true,
                'message' => 'Add Shipment Memo',
                'modal' => $this->render('SplicedCommerceAdminBundle:OrderShipment:add_memo_modal.html.twig',array(
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
