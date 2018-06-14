<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ManufacturerFilterType;

/**
 * ManufacturerController
 *
 * @Route("/manufacturer")
 */
class ManufacturerController extends BaseFilterableController
{
    
    const FILTER_TAG = 'commerce.manufacturer';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\Type\ManufacturerFilterType';
    
    /**
     * @Route("/", name="commerce_admin_manufacturer")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {

        // load products
        $manufacturers = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
              ->getRepository('SplicedCommerceAdminBundle:Manufacturer')
              ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
         
        $filterForm = $this->createForm(new ManufacturerFilterType());

        return array(
            'manufacturers' => $manufacturers,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * @Route("/new", name="commerce_admin_manufacturer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        $form = $this->get('commerce.admin.form_factory')->createManufacturerForm();
        
        return array(
            'form'   => $form->createView(),
        );
    }
    

    /**
     * @Route("/save", name="commerce_admin_manufacturer_save")
     * @Template("SplicedCommerceAdminBundle:Manufacturer:new.html.twig")
     * @Method({"POST","PUT"})
     */
    public function saveAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createManufacturerForm();
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
                
            $this->get('commerce.admin.entity_manager')->persist($form->getData());
            $this->get('commerce.admin.entity_manager')->flush();
        
            $this->get('session')->getFlashBag()->add('success', 'Manufacturer Successfully Added');
            return $this->redirect($this->generateUrl('commerce_admin_manufacturer'));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
         
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * @Route("/edit/{id}", name="commerce_admin_manufacturer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $maufacturer = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:Manufacturer')
        ->findOneById($id);
        
        if(!$maufacturer) {
            throw $this->createNotFoundException('Unable to find Manufacturer.');
        }
        
        $form = $this->get('commerce.admin.form_factory')
        ->createManufacturerForm($maufacturer);
        
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'maufacturer' => $maufacturer,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * @Route("/update/{id}", name="commerce_admin_manufacturer_update")
     * @Method({"POST","PUT"})
     * @Template("SplicedCommerceAdminBundle:Manufacturer:edit.html.twig")
     */
    public function updateAction($id)
    {
        $maufacturer = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:Manufacturer')
        ->findOneById($id);
        
        if(!$maufacturer) {
            throw $this->createNotFoundException('Unable to find Manufacturer.');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $form = $this->get('commerce.admin.form_factory')->createManufacturerForm($maufacturer);
        
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            $this->get('commerce.admin.entity_manager')->persist($maufacturer);
            $this->get('commerce.admin.entity_manager')->flush();
        
            return $this->redirect($this->generateUrl('commerce_admin_manufacturer_edit', array('id' => $id)));
        }
        
        return array(
            'maufacturer'   => $maufacturer,
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * @Route("/delete/{id}", name="commerce_admin_manufacturer_delete")
     * @Method("GET")
     * @Template()
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);

        if ($form->bind($this->getRequest()) && $form->isValid()) {
            $manufacturer = $this->get('commerce.admin.entity_manager')
            ->getRepository('SplicedCommerceAdminBundle:Manufacturer')
            ->findOneById($id);
        
            if (!$manufacturer) {
                throw $this->createNotFoundException('Unable to find Manufacturer.');
            }
        
            $this->get('commerce.admin.entity_manager')->remove($manufacturer);
            $this->get('commerce.admin.entity_manager')->flush();
        }
        
        return $this->redirect($this->generateUrl('commerce_admin_manufacturer'));
    }
    

    /**
     * Creates a form to delete a Product entity by id.
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
     * Filters the list of entities for Product entity.
     *
     * @Route("/filter", name="commerce_admin_manufacturer_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
        return $this->redirect($this->generateUrl('commerce_admin_manufacturer'));
    }
    
    
    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_manufacturer_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
        return $this->redirect($this->generateUrl('commerce_admin_manufacturer'));
    }
    
    
    /**
     * Deletes a Product entity.
     *
     * @Route("/batch", name="commerce_admin_manufacturer_batch")
     * @Method("POST")
     */
    public function batchAction()
    {
        $ids = $this->getRequest()->request->get('ids');
        $action = $this->getRequest()->request->get('batchAction');
        $methodName = 'batch'.ucwords($action);
    
        if(method_exists($this,$methodName)) {
            return call_user_func($this, $methodName, $ids);
        }
    
        throw new \InvalidArgumentException(sprintf('Method %s does not exist',$methodName));
    }
    
}