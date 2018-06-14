<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\CheckoutCustomFieldFilterType;

/**
 * CheckoutCustomField controller.
 *
 * @Route("/checkout-custom-field")
 */
class CheckoutCustomFieldController extends BaseFilterableController
{
    const FILTER_TAG = 'commerce.checkout_custom_field';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\CheckoutCustomFieldFilterType';

    /**
     * Lists all CheckoutCustomFields.
     *
     * @Route("/", name="commerce_admin_checkout_custom_field")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:CheckoutCustomField:list.html.twig")
     */
    public function indexAction()
    {
        // load checkout_custom_fields
        $checkout_custom_fields = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
                ->getRepository('SplicedCommerceAdminBundle:CheckoutCustomField')
                ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
        
        $filterForm = $this->createForm(new CheckoutCustomFieldFilterType());

        return array(
            'checkout_custom_fields' => $checkout_custom_fields,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * 
     *
     * @Route("/new", name="commerce_admin_checkout_custom_field_new")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:CheckoutCustomField:new.html.twig")
     */
    public function newAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createCheckoutCustomFieldForm();
            
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Save CheckoutCustomField
     *
     * @Route("/save", name="commerce_admin_checkout_custom_field_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:CheckoutCustomField:new.html.twig")
     */
    public function saveAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createCheckoutCustomFieldForm();
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $checkout_custom_field = $form->getData();
            
            $this->get('commerce.admin.entity_manager')->persist($checkout_custom_field);
            $this->get('commerce.admin.entity_manager')->flush();

            return $this->redirect($this->generateUrl('commerce_admin_checkout_custom_field_edit', array(
                'id' => $checkout_custom_field->getId()
            )));
        }

        return array(
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing CheckoutCustomField entity.
     *
     * @Route("/edit/{id}", name="commerce_admin_checkout_custom_field_edit")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:CheckoutCustomField:edit.html.twig")
     */
    public function editAction($id)
    {
        $checkout_custom_field = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:CheckoutCustomField')
          ->findOneById($id);
        
        if (!$checkout_custom_field) {
            throw $this->createNotFoundException('CheckoutCustomField Not Found');
        }

        $form = $this->get('commerce.admin.form_factory')->createCheckoutCustomFieldForm($checkout_custom_field);
        
        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Edits an existing CheckoutCustomField entity.
     *
     * @Route("/update/{id}", name="commerce_admin_checkout_custom_field_update")
     * @Method({"PUT","POST"})
     * @Template("SplicedCommerceAdminBundle:CheckoutCustomField:edit.html.twig")
     */
    public function updateAction($id)
    {
        $checkout_custom_field = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:CheckoutCustomField')
          ->findOneById($id);
        
        if (!$checkout_custom_field) {
            throw $this->createNotFoundException('CheckoutCustomField Not Found');
        }

        $form = $this->get('commerce.admin.form_factory')->createCheckoutCustomFieldForm($checkout_custom_field);
        

            
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $checkout_custom_field = $form->getData();
            
            $this->get('commerce.admin.entity_manager')->persist($checkout_custom_field);
            $this->get('commerce.admin.entity_manager')->flush();

            return $this->redirect($this->generateUrl('commerce_admin_checkout_custom_field_edit', array(
                'id' => $checkout_custom_field->getId()
            )));
        }

        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Deletes a CheckoutCustomField entity.
     *
     * @Route("/delete/{id}", name="commerce_admin_checkout_custom_field_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:CheckoutCustomField')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CheckoutCustomField entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('checkout_custom_field'));
    }

    /**
     * Creates a form to delete a CheckoutCustomField entity by id.
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
     * Filters the list of entities for CheckoutCustomField entity.
     *
     * @Route("/filter", name="commerce_admin_checkout_custom_field_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $form   = $this->createForm(new CheckoutCustomFieldFilterType());
        $filters = array();
        
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            $session->set('filter.checkout_custom_field', serialize($filters));
        }
        

        $this->get('session')->getFlashBag()->add('notice', 'CheckoutCustomField Filters Updated');
        return $this->redirect($this->generateUrl('commerce_admin_checkout_custom_field'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_checkout_custom_field_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->get('session')->setFilters($filters);
        $this->get('session')->getFlashBag()->add('notice', 'CheckoutCustomField Filters Cleared');
        return $this->redirect($this->generateUrl('commerce_admin_checkout_custom_field'));
    }
    
    /**
     * Deletes a CheckoutCustomField entity.
     *
     * @Route("/batch", name="commerce_admin_checkout_custom_field_batch")
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
        $entities = $em->getRepository('SplicedCommerceAdminBundle:CheckoutCustomField')->findById($id);
        
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
        
        return $this->redirect($this->generateUrl('checkout_custom_field'));
    }

}
