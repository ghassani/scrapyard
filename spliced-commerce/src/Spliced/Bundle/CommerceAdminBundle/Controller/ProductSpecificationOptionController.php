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
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductSpecificationOptionFilterType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductSpecificationOptionController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @Route("/product-specification-option")
 */
class ProductSpecificationOptionController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.product.specification_option';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Model\ListFilter';
    
    /**
     * @Route("/", name="commerce_admin_product_specification_option")
     * @Template()
     */
    public function listAction()
    {

        // load orders
        $specificationOptions = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
              ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
              ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
        
        $filterForm = $this->createForm(new ProductSpecificationOptionFilterType());
        
        return array(
            'specificationOptions' => $specificationOptions,
            'filterForm' => $filterForm->createView(),
        );
    }
    
    /**
     * @Route("/new", name="commerce_admin_product_specification_option_new")
     * @Template()
     */
    public function newAction()
    {

        $form = $this->get('commerce.admin.form_factory')
          ->createProductSpecificationOptionForm();
        
        return array(
           'form'   => $form->createView(),
        );
    }
    
    /**
     * @Route("/save", name="commerce_admin_product_specification_option_save")
     * @Method({"POST","PUT"})
     * @Template("SplicedCommerceAdminBundle:ProductSpecificationOption:new.html.twig")
     */
    public function saveAction()
    {
        $form = $this->get('commerce.admin.form_factory')
          ->createProductSpecificationOptionForm();
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {
            
            $specificationOption = $form->getData();
    
            $this->get('commerce.product_specification_option_manager')->save($specificationOption);
            
            $this->get('session')->getFlashBag()->add('success', 'Product Specification Option Successfully Created. Now add some values to this option.');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_specification_option_edit', array(
                'id' => $specificationOption->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
        
    
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * @Route("/edit/{id}", name="commerce_admin_product_specification_option_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $specificationOption = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
          ->findOneById($id);

        if(!$specificationOption) {
            throw $this->createNotFoundException('Unable to find Product Specification Option.');
        }

        $form = $this->get('commerce.admin.form_factory')
          ->createProductSpecificationOptionForm($specificationOption);

        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        ); 
    }
    
    /**
     * @Route("/update/{id}", name="commerce_admin_product_specification_option_update")
     * @Method({"POST","PUT"})
     * @Template("SplicedCommerceAdminBundle:ProductSpecificationOption:edit.html.twig")
     */
    public function updateAction($id)
    {
        $specificationOption = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
          ->findOneById($id);

        if(!$specificationOption) {
            throw $this->createNotFoundException('Unable to find Product Specification Option.');
        }
        
        $form = $this->get('commerce.admin.form_factory')
          ->createProductSpecificationOptionForm($specificationOption);
                
        if($form->bind($this->getRequest()) && $form->isValid()){
            
            $specificationOption = $form->getData();
    
                        
            $this->get('commerce.product_specification_option_manager')->update($specificationOption);
            
            $this->get('session')->getFlashBag()->add('success', 'Product Specification Option Successfully Updated.');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_specification_option_edit', array(
                'id' => $specificationOption->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
                
        return array(
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
        );
    }
    

    /**
     * @Route("/delete/{id}", name="commerce_admin_product_specification_option_delete")
     */
    public function deleteAction($id)
    {
        $specificationOption = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
        ->findOneById($id);
         
        if(!$specificationOption) {
            throw $this->createNotFoundException('Unable to find Product Specification Option.');
        }
         
        $this->get('commerce.product_specification_option_manager')->delete($specificationOption);
         
        $this->get('session')->getFlashBag()->add('success', 'Product Specification Option Sucessfully Deleted');
        return $this->redirect($this->generateUrl('commerce_admin_product_specification_option'));
    }
    
    /**
     * @Route("/delete-value/{id}/{valueId}", name="commerce_admin_product_specification_option_delete_value")
     * @Method({"POST","DELETE","GET"})
     */
    public function deleteValueAction($id, $valueId)
    {
        
        $specification = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
        ->findOneById($id);
        
        if(!$specification){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Specification Option Not Found',
                    'specification_option_id' => $id,
                    'value_id' => $valueId,
                ));
            }
            throw $this->createNotFoundException('Specification Option Not Found');
        }
        
        $specificationValue = null;
        foreach($specification->getValues() as $value){
            if ($value->getId() == $valueId) {
                $specificationValue = $value;
            }
        }
        
        if(!$specificationValue){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Specification Option Value Not Found',
                    'specification_option_id' => $specification->getId(),
                    'value_id' => $valueId,
                ));
            }
            throw $this->createNotFoundException('Specification Option Value Not Found');
        }
        
        $this->get('commerce.admin.entity_manager')->remove($specificationValue);
        $this->get('commerce.admin.entity_manager')->flush();
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_SPECIFICATION_OPTION_UPDATE,
            new Events\ProductSpecificationOptionUpdateEvent($specification)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'specification_option_id' => $specification->getId(),
                'value_id' => $valueId,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Specification Option Value Successfully Deleted');
        
        return $this->redirect($this->generateUrl('commerce_admin_product_specification_option_edit', array(
            'id' => $specification->getId(),
        )));
    }
    
    /**
     * Filters the list of entities for Order entity.
     *
     * @Route("/filter", name="commerce_admin_product_specification_option_filter")
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
     * @Route("/filter/reset", name="commerce_admin_product_specification_option_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
    }
    
    /**
     * @Route("/check-name", name="commerce_admin_product_specification_option_check_name")
     */
    public function checkNameAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Invalid Request Type');
        } else if(!$this->getRequest()->request->has('name')){
            throw new \InvalidArgumentException('Name POST variable required to check route');
        }
        
        try{
            $specificationOption = $this->get('commerce.admin.entity_manager')
            ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
            ->findOneByName($this->getRequest()->request->get('name'));
                
        } catch(NoResultException $e) {
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Specification Option Does Not Exist',
            ));
        }
        
        return new JsonResponse(array(
            'success' => false,
            'message' => 'Specification Option Exists',
            'id' => $specificationOption->getId(),
        ));
    }
    
    /**
     * @Route("/get-option/{id}", name="commerce_admin_product_specification_option_get")
     */
    public function getOptionAction($id)
    {
    	if(!$this->get('request')->isXmlHttpRequest()){
    		throw $this->createNotFoundException();
    	}
    	
        $option = $this->get('commerce.admin.entity_manager')
         ->getRepository('SplicedCommerceAdminBundle:ProductSpecificationOption')
         ->createQueryBuilder('specification')
         ->select('specification')
         ->where('specification.id = :id')
         ->setParameter('id', $id)
         ->getQuery()
         ->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        if(!$option){
            return new JsonResponse(array(
               'error' => 'Product Specification Option Not Found',     
            ));
        }
        return new JsonResponse($option);
    }
    

}
