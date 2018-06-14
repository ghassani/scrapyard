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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductAttributeOptionFilterType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductAttributeOptionType;
use SplicedComponent\Commerce\Form\Type\Admin\ProductAttributeOptionUserDataInputType;
use Spliced\Component\Commerce\Form\Type\Admin\ProductAttributeOptionUserDataSelectionType;
use Spliced\Bundle\CommerceAdminBundle\Entity\ProductAttributeOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductAttributeOptionController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @Route("/product-attribute-option")
 */
class ProductAttributeOptionController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.product.attribute_option';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Model\ListFilter';
    
    /**
     * @Route("/", name="commerce_admin_product_attribute_option")
     * @Template()
     */
    public function listAction()
    {

        // load orders
        $attributeOptions = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
              ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
              ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
        
        $filterForm = $this->createForm(new ProductAttributeOptionFilterType());
        
        return array(
            'attributeOptions' => $attributeOptions,
            'filterForm' => $filterForm->createView(),
        );
    }
    
    /**
     * @Route("/new", name="commerce_admin_product_attribute_option_new")
     * @Template()
     */
    public function newAction()
    {

        $form = $this->get('commerce.admin.form_factory')
          ->createProductAttributeOptionForm();
        
        return array(
           'form'   => $form->createView(),
        );
    }
    
    /**
     * @Route("/save", name="commerce_admin_product_attribute_option_save")
     * @Method({"POST","PUT"})
     * @Template("SplicedCommerceAdminBundle:ProductAttributeOption:new.html.twig")
     */
    public function saveAction()
    {
        $form = $this->get('commerce.admin.form_factory')
          ->createProductAttributeOptionForm();
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $attributeOption = $form->getData();
    
            $this->get('commerce.product_attribute_option_manager')->save($attributeOption);
            
            $this->get('session')->getFlashBag()->add('success', 'Product Attribute Option Successfully Created. Now add some values to this option.');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_attribute_option_edit', array(
                'id' => $attributeOption->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
        
    
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * @Route("/edit/{id}", name="commerce_admin_product_attribute_option_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $attributeOption = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
          ->findOneById($id);

        if(!$attributeOption) {
            throw $this->createNotFoundException('Unable to find Product Attribute Option.');
        }

        $form = $this->get('commerce.admin.form_factory')
          ->createProductAttributeOptionForm($attributeOption);

        $viewVars = array();
        
        return array_merge($viewVars, array(
            'attributeOption'     => $attributeOption,
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        )); 
    }
    
    /**
     * @Route("/update/{id}", name="commerce_admin_product_attribute_option_update")
     * @Method({"POST","PUT"})
     * @Template("SplicedCommerceAdminBundle:ProductAttributeOption:edit.html.twig")
     */
    public function updateAction($id)
    {
        $attributeOption = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
          ->findOneById($id);

        if(!$attributeOption) {
            throw $this->createNotFoundException('Unable to find Product Attribute Option.');
        }
        
        $form = $this->get('commerce.admin.form_factory')
          ->createProductAttributeOptionForm($attributeOption);
                
        if($form->bind($this->getRequest()) && $form->isValid()){
            
            $attributeOption = $form->getData();
                
            $this->get('commerce.product_attribute_option_manager')->update($attributeOption);
            
            $this->get('session')->getFlashBag()->add('success', 'Product Attribute Option Successfully Updated.');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_attribute_option_edit', array(
                'id' => $attributeOption->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
                
        return array(
            'attributeOption' => $attributeOption,
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }
    
    /**
     * @Route("/delete/{id}", name="commerce_admin_product_attribute_option_delete")
     */
    public function deleteAction($id)
    {
        $attributeOption = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
        ->findOneById($id);
        
        if(!$attributeOption) {
            throw $this->createNotFoundException('Unable to find Product Attribute Option.');
        }
        
        $this->get('commerce.product_attribute_option_manager')->delete($attributeOption);
        
        $this->get('session')->getFlashBag()->add('success', 'Product Attribute Option Sucessfully Deleted');
        return $this->redirect($this->generateUrl('commerce_admin_product_attribute_option'));
    }
        
    /**
     * @Route("/delete-value/{id}/{valueId}", name="commerce_admin_product_attribute_option_delete_value")
     * @Method({"POST","DELETE"})
     */
    public function deleteValueAction($id, $valueId)
    {
        $attributeOption = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
          ->findOneById($id);

        if(!$attributeOption) {
            throw $this->createNotFoundException('Unable to find Product Attribute Option.');
        }

        try{
        
            $attributeOptionValue = $this->getDoctrine()
            ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOptionValue')
            ->findOneById($valueId);
        
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Product Attribute Option Value.');
        }
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_ATTRIBUTE_OPTION_VALUE_DELETE,
            new Events\ProductAttributeOptionValueDeleteEvent($attributeOptionValue)
        );
        
        if($this->getRequest()->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Attribute Option Value Successfully Deleted.');
        
        return $this->redirect($this->generateUrl('commerce_admin_product_attribute_option_edit', array(
            'id' => $attributeOption->getId()
        )));
    }
    
    /**
     * Filters the list of entities for Order entity.
     *
     * @Route("/filter", name="commerce_admin_product_attribute_option_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
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
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_product_attribute_option_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
    }
    
    /**
     * @Route("/check-name", name="commerce_admin_product_attribute_option_check_name")
     */
    public function checkkNameAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Invalid Request Type');
        } else if(!$this->getRequest()->request->has('name')){
            throw new \InvalidArgumentException('Name POST variable required to check route');
        }
        
        try{
            $attributeOption = $this->get('commerce.admin.entity_manager')
            ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
            ->findOneByName($this->getRequest()->request->get('name'));
                
        } catch(NoResultException $e) {
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Attribute Option Does Not Exist',
            ));
        }
        
        return new JsonResponse(array(
            'success' => false,
            'message' => 'Attribute Option Exists',
            'id' => $attributeOption->getId(),
        ));
    }
    
    /**
     * @Route("/get-option/{id}", name="commerce_admin_product_attribute_option_get")
     */
    public function getOptionAction($id)
    {
        $option = $this->get('commerce.admin.entity_manager')
         ->getRepository('SplicedCommerceAdminBundle:ProductAttributeOption')
         ->createQueryBuilder()
         ->field('id')->equals($id)
         ->hydrate(false)
         ->getQuery()
         ->getSingleResult();
        
        if(!$option){
            return new JsonResponse(array(
               'error' => 'Product Attribute Option Not Found',     
            ));
        }
        return new JsonResponse($option);
    }
}
