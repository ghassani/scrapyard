<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Entity\Customer;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\CustomerType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\CustomerFilterType;

/**
 * Customer controller.
 *
 * @Route("/customer")
 */
class CustomerController extends Controller
{

    /**
     * Lists all Customer entities.
     *
     * @Route("/", name="commerce_admin_customer")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // load products
        $entities = $this->get('knp_paginator')->paginate(
            $em->getRepository('SplicedCommerceAdminBundle:Customer')->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
        
        $filterForm = $this->createForm(new CustomerFilterType());

        return array(
            'entities' => $entities,
            'filterForm' => $filterForm->createView(),
        );
    }
    /**
     * Creates a new Customer entity.
     *
     * @Route("/", name="commerce_admin_customer_create")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Customer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Customer();
        $form = $this->createForm(new CustomerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('customer'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Customer entity.
     *
     * @Route("/new", name="commerce_admin_customer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Customer();
        $form   = $this->createForm(new CustomerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Customer entity.
     *
     * @Route("/{id}/edit", name="commerce_admin_customer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        try{
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Customer')->findOneById($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Customer.');
        }

        $editForm = $this->createForm(new CustomerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Customer entity.
     *
     * @Route("/{id}", name="commerce_admin_customer_update")
     * @Method("PUT")
     * @Template("SplicedCommerceAdminBundle:Customer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SplicedCommerceAdminBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CustomerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('customer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("/{id}", name="commerce_admin_customer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Customer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Customer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('customer'));
    }

    /**
     * Creates a form to delete a Customer entity by id.
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
     * Filters the list of entities for Customer entity.
     *
     * @Route("/filter", name="commerce_admin_customer_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $form   = $this->createForm(new CustomerFilterType());
        $filters = array();
        
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            $session->set('filter.customer', serialize($filters));
        }
        

        $this->get('session')->getFlashBag()->add('notice', 'Customer Filters Updated');
        return $this->redirect($this->generateUrl('commerce_admin_customer'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_customer_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->get('session')->setFilters($filters);
        $this->get('session')->getFlashBag()->add('notice', 'Customer Filters Cleared');
        return $this->redirect($this->generateUrl('commerce_admin_customer'));
    }
    
    
    /**
     * Gets the currently applied filters
     *
     * @return array
     */
    private function getFilters()
    {
        return $this->get('session')->has('filter.customer') ?
          unserialize($this->get('session')->get('filter.customer')) : new ListFilter();
    }
   
    /**
     * Gets the currently applied filters
     *
     * @return array
     */
    private function setFilters(ListFilter $filters)
    {
        $this->get('session')->set('filter.customer', $filters->serialize());
        return $this;
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("/batch", name="commerce_admin_customer_batch")
     * @Method("POST")
     */
    public function batchAction()
    {
        $ids = $this->getRequest()->request->get('ids');
        $action = $this->getRequest()->request->get('action');
        $methodName = 'batch'.ucwords($action);

        if(method_exists($this,$methodName)) {
            return call_user_func($this, $methodName, $ids);
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
        $entities = $em->getRepository('SplicedCommerceAdminBundle:Customer')->findById($id);
        
        $count = count($entities);
        
        foreach($entities as $entity) {
            $em->remove($entity);
        }
        
        try{
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('Successfully deleted %s records.', $count));
        } catch( \Exception $e) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Error deleting Records. Error: %s', $e->getMessage()));
        }
        
        return $this->redirect($this->generateUrl('customer'));
    }

}
