<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Entity\Order;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\OrderType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\OrderFilterType;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * OrderController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @Route("/order")
 */
class OrderController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.order';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\Type\OrderFilterType';
    
    /**
     * Lists all Order entities.
     *
     * @Route("/", name="commerce_admin_order")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // load orders
        $orders = $this->get('knp_paginator')->paginate(
            $em->getRepository('SplicedCommerceAdminBundle:Order')->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit',25)
        );
        
        $filterForm = $this->createForm(new OrderFilterType(), $this->getFilters());

        return array(
            'orders' => $orders,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * Lists all Incomplete Order entities.
     *
     * @Route("/incomplete", name="commerce_admin_order_incomplete")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Order:incomplete.html.twig")
     */
    public function incompleteAction()
    {
        $em = $this->getDoctrine()->getManager();
    
        $query = $em->getRepository('SplicedCommerceAdminBundle:Order')
            ->getAdminIncompleteListQuery($this->getFilters('incomplete'));
        
        // load orders
        $entities = $this->get('knp_paginator')->paginate(
            $query,
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
    
        $filterForm = $this->createForm(new OrderFilterType());
    
        return array(
                'entities' => $entities,
                'filterForm' => $filterForm->createView(),
        );
    }
    
    /**
     * Creates a new Order entity.
     *
     * @Route("/", name="commerce_admin_order_create")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Order:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $order  = new Order();
        $form = $this->createForm(new OrderType(), $order);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirect($this->generateUrl('order'));
        }

        return array(
            'order' => $order,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="commerce_admin_order_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Order();
        $form   = $this->createForm(new OrderType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="commerce_admin_order_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        try{
            $order = $em->getRepository('SplicedCommerceAdminBundle:Order')->findOneById($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $editForm = $this->createForm(new OrderType(), $order);
        $deleteForm = $this->createDeleteForm($id);


        return array(
            'order'      => $order,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}", name="commerce_admin_order_update")
     * @Method("PUT")
     * @Template("SplicedCommerceAdminBundle:Order:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SplicedCommerceAdminBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrderType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('order_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/view", name="commerce_admin_order_view")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Order:view.html.twig")
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
    
        try{
            $order = $em->getRepository('SplicedCommerceAdminBundle:Order')->findOneById($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Order.');
        }    
    
        return array(
            'order'      => $order,
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }
    
    /**
     * Deletes a Order entity.
     *
     * @Route("/{id}", name="commerce_admin_order_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Order')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Order entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     * Creates a form to delete a Order entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Filters the list of entities for Order entity.
     *
     * @Route("/filter", name="commerce_admin_order_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
        return $this->redirect($this->generateUrl('commerce_admin_order'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_order_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::clearFiltersActions();
        return $this->redirect($this->generateUrl('commerce_admin_order'));
    }
    
    
    /**
     * Deletes a Order entity.
     *
     * @Route("/batch", name="commerce_admin_order_batch")
     * @Method("POST")
     */
    public function batchAction()
    {
        $ids = $this->getRequest()->request->get('ids');
        
        if(!count($ids)){
            $this->get('session')->getFlashBag()->add('error', 'No orders were selected');
            return $this->redirect($this->generateUrl('commerce_admin_order'));
        }
        
        $action = $this->getRequest()->request->get('batchAction');
        $methodName = 'batch'.str_replace(' ','',ucwords($action));

        if(method_exists($this,$methodName)) {
            return call_user_func(array($this, $methodName), $ids);
        }
        
        throw new \InvalidArgumentException(sprintf('Method %s does not exist',$methodName));
    }
    

    /**
    * batchDelete
    * 
    * @param array $ids
    */
    protected function batchDelete(array $ids)
    {
        $orders = $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Order')->findById($ids);
        
        $count = count($orders);
        
        $deleted = 0;
        $undeleteable = 0;
        foreach($orders as $order) {
            if(!in_array($order->getOrderStatus(), array(
                OrderInterface::STATUS_CANCELLED,
                OrderInterface::STATUS_ABANDONED
            ))){
                $undeleteable++;
                continue;
            }
            $this->getDoctrine()->getManager()->remove($order);
            $deleted++;
        } 
        
        try{
            $this->getDoctrine()->getManager()->flush();
            
            if($deleted > 0){
                $this->get('session')->getFlashBag()->add('success', sprintf('Successfully deleted %s orders.', $count));
            }
            if($undeleteable > 0){
                $this->get('session')->getFlashBag()->add('error', sprintf('%s orders could not be deleted. Their current status prevents them from being deleted. You will need to change their status to cancelled before deleting.', $undeleteable));
            }
        } catch( \Exception $e) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Error deleting Records. Error: %s', $e->getMessage()));
        }
        
        return $this->redirect($this->generateUrl('commerce_admin_order'));
    }
    
    /**
     * @Route("/{id}/incomplete-followup", name="commerce_admin_order_incomplete_followup")
     * @Method("GET")
     * @Template()
     */
    public function incompleteOrderFollowupAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        try{
            $order = $em->getRepository('SplicedCommerceAdminBundle:Order')->findOneById($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Order.');
        }
        
        // available for notification
        if(!in_array($order->getOrderStatus(), array(OrderInterface::STATUS_INCOMPLETE,OrderInterface::STATUS_ABANDONED))){ 
            $this->get('session')->getFlashBag()->add('error', 'Order is not in a status to have a incomplete order follow up email');
            return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
        } else if(! $order->getEmail()){
            $this->get('session')->getFlashBag()->add('error', 'Order has no email address to send an incomplete order follow up email');
            return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
        }
        
        // check if we have already sent one
        $currentDateTime = new \DateTime('now');
        foreach($order->getMemos() as $memo){
            if($memo->getNotificationType() == 'incomplete_order_followup'){
                if(!$currentDateTime->diff($memo->getCreatedAt())->format('%d')){
                    $this->get('session')->getFlashBag()->add('error', 'Notification Has Already Been Sent in the past 24 hours');
                    return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
                }
            }
        }
        
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_ORDER_INCOMPLETE_FOLLOWUP,
            new Events\OrderUpdateEvent($order)
        );
        
        $this->get('session')->getFlashBag()->add('success', 'Customer Successfully Notified');
        return $this->redirect($this->generateUrl('commerce_admin_order_edit', array('id' => $order->getId())));
    }

}
