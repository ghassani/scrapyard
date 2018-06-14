<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\AffiliateFilterType;

/**
 * Affiliate controller.
 *
 * @Route("/affiliate")
 */
class AffiliateController extends BaseFilterableController
{
    const FILTER_TAG = 'commerce.affiliate';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\AffiliateFilterType';

    /**
     * Lists all Affiliates.
     *
     * @Route("/", name="commerce_admin_affiliate")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Affiliate:list.html.twig")
     */
    public function indexAction()
    {
        // load affiliates
        $affiliates = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.document_manager')
                ->getRepository('SplicedCommerceAdminBundle:Affiliate')
                ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
        
        $filterForm = $this->createForm(new AffiliateFilterType());

        return array(
            'affiliates' => $affiliates,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * 
     *
     * @Route("/new", name="commerce_admin_affiliate_new")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Affiliate:new.html.twig")
     */
    public function newAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createAffiliateForm();
            
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Save Affiliate
     *
     * @Route("/save", name="commerce_admin_affiliate_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Affiliate:new.html.twig")
     */
    public function saveAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createAffiliateForm();
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $affiliate = $form->getData();
            
            $this->get('commerce.admin.document_manager')->persist($affiliate);
            $this->get('commerce.admin.document_manager')->flush();

            return $this->redirect($this->generateUrl('commerce_admin_affiliate_edit', array(
                'id' => $affiliate->getId()
            )));
        }

        return array(
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Affiliate entity.
     *
     * @Route("/edit/{id}", name="commerce_admin_affiliate_edit")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Affiliate:edit.html.twig")
     */
    public function editAction($id)
    {
        $affiliate = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Affiliate')
          ->findOneById($id);
        
        if (!$affiliate) {
            throw $this->createNotFoundException('Affiliate Not Found');
        }

        $form = $this->get('commerce.admin.form_factory')->createAffiliateForm($affiliate);
        
        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Edits an existing Affiliate entity.
     *
     * @Route("/update/{id}", name="commerce_admin_affiliate_update")
     * @Method({"PUT","POST"})
     * @Template("SplicedCommerceAdminBundle:Affiliate:edit.html.twig")
     */
    public function updateAction($id)
    {
        $affiliate = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Affiliate')
          ->findOneById($id);
        
        if (!$affiliate) {
            throw $this->createNotFoundException('Affiliate Not Found');
        }

        $form = $this->get('commerce.admin.form_factory')->createAffiliateForm($affiliate);
        

            
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $affiliate = $form->getData();
            
            $this->get('commerce.admin.document_manager')->persist($affiliate);
            $this->get('commerce.admin.document_manager')->flush();

            return $this->redirect($this->generateUrl('commerce_admin_affiliate_edit', array(
                'id' => $affiliate->getId()
            )));
        }

        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }

    /**
     * Deletes a Affiliate entity.
     *
     * @Route("/delete/{id}", name="commerce_admin_affiliate_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Affiliate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Affiliate entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('affiliate'));
    }

    /**
     * Creates a form to delete a Affiliate entity by id.
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
     * Filters the list of entities for Affiliate entity.
     *
     * @Route("/filter", name="commerce_admin_affiliate_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $form   = $this->createForm(new AffiliateFilterType());
        $filters = array();
        
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            $session->set('filter.affiliate', serialize($filters));
        }
        

        $this->get('session')->getFlashBag()->add('notice', 'Affiliate Filters Updated');
        return $this->redirect($this->generateUrl('commerce_admin_affiliate'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_affiliate_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->get('session')->setFilters($filters);
        $this->get('session')->getFlashBag()->add('notice', 'Affiliate Filters Cleared');
        return $this->redirect($this->generateUrl('commerce_admin_affiliate'));
    }
    
    /**
     * Deletes a Affiliate entity.
     *
     * @Route("/batch", name="commerce_admin_affiliate_batch")
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
        $entities = $em->getRepository('SplicedCommerceAdminBundle:Affiliate')->findById($id);
        
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
        
        return $this->redirect($this->generateUrl('affiliate'));
    }

}
