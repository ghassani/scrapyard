<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\CategoryFilterType;
use Spliced\Component\Commerce\Event as Events;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.category';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\Type\CategoryFilterType';
    
    /**
     * @Route("/", name="commerce_admin_category")
     * @Method("GET")
     * @Template()
     */
    public function treeAction()
    {
        $categories = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Category')
          ->getRootNodes('position', 'asc');
      
        return array(
            'categories' => $categories,
        );
    }
    
    /**
     * Lists all Category entities.
     *
     * @Route("/list", name="commerce_admin_category_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $om = $this->get('commerce.admin.entity_manager');
        
        // load categorys
        $categories = $this->get('knp_paginator')->paginate(
            $om->getRepository('SplicedCommerceAdminBundle:Category')->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
        
        $filterForm = $this->createForm(new CategoryFilterType());
        
        
        return array(
            'categories' => $categories,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="commerce_admin_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->get('commerce.admin.form_factory')->createCategoryForm();

        return array(
            'category' => $form->getData(),
            'form'   => $form->createView(),
        );
    }

    /**
     * Saves a new Category entity.
     *
     * @Route("/save", name="commerce_admin_category_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Category:new.html.twig")
     */
    public function saveAction(Request $request)
    {
    
        $form = $this->get('commerce.admin.form_factory')->createCategoryForm();        
        
        if ($form->bind($this->getRequest()) && $form->isValid()) {

            $category = $form->getData();
            
            $this->get('commerce.category_manager')->save($category);
                
            $this->get('session')->getFlashBag()->add('success', 'Category Successfully Added');
            
            if($this->getRequest()->request->has('add_another')){
                return $this->redirect($this->generateUrl('commerce_admin_category_new'));
            } else {
                return $this->redirect($this->generateUrl('commerce_admin_category_edit', array('id' => $category->getId())));
            }
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data');
        
        return array(
            'form'   => $form->createView(),
        );
    }
    
    
    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/edit/{id}", name="commerce_admin_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

        $category = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Category')
          ->findOneById($id);
            
        if(!$category){
            throw $this->createNotFoundException('Category Not Found.');
        }

        $editForm = $this->get('commerce.admin.form_factory')->createCategoryForm($category);
        
        $deleteForm = $this->createDeleteForm($id);
 
        if($this->getRequest()->isXmlHttpRequest()){
            return $this->render('SplicedCommerceAdminBundle:Category:edit_content.html.twig',array(
                'category'     => $category,
                'form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }

        return array(
            'category'     => $category,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Updates an existing Category entity.
     *
     * @Route("/update/{id}", name="commerce_admin_category_update")
     * @Method({"PUT","POST"})
     * @Template("SplicedCommerceAdminBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $category = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Category')
          ->findOneById($id);
            
        if(!$category){
            throw $this->createNotFoundException('Category Not Found.');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $form = $this->get('commerce.admin.form_factory')->createCategoryForm($category);

        if ($form->bind($this->getRequest()) && $form->isValid()) {

            $category = $form->getData();
            
            $this->get('commerce.category_manager')->update($category);
                
            $this->get('session')->getFlashBag()->add('success', 'Category Successfully Updated');
            
            return $this->redirect($this->generateUrl('commerce_admin_category_edit', array('id' => $category->getId())));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data');

        return array(
            'category'    => $category,
            'form'   => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/delete/{id}", name="commerce_admin_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {

            $category = $this->get('commerce.admin.entity_manager')
              ->getRepository('SplicedCommerceAdminBundle:Category')->find($id);

            if (!$category) {
                throw $this->createNotFoundException('Unable to find Category');
            }

            $this->get('commerce.category_manager')->delete($category);
        }

        return $this->redirect($this->generateUrl('commerce_admin_category'));
    }

    /**
     * Creates a form to delete a Category entity by id.
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
     * Filters the list of entities for Category entity.
     *
     * @Route("/filter", name="commerce_admin_category_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_category_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
    }


    /**
     * Deletes a Category entity.
     *
     * @Route("/batch", name="commerce_admin_category_batch")
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
        $entities = $em->getRepository('SplicedCommerceAdminBundle:Category')->findById($id);
        
        $count = count($entities);
        
        foreach($entities as $entity) {;
            $this->get('commerce.category_manager')->delete($category, false);
        }
        
        try{
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('Successfully deleted %s records.', $count));
        } catch( \Exception $e) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Error deleting Records. Error: %s', $e->getMessage()));
        }
        
        return $this->redirect($this->generateUrl('category'));
    }
    
    /**
     * checkUrlSlugAction
     *
     * @Route("/check-url-slug", name="commerce_admin_category_check_slug")
     * @Template()
     */
    public function checkUrlSlugAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Invalid Request Type');
        } else if(!$this->getRequest()->request->has('slug')){
            throw new \InvalidArgumentException('Slug POST variable required to check route');
        }
    
        $urlSlug = preg_replace('/^\//', '', $this->getRequest()->request->get('slug'));

        $category = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:Category')
        ->findOneByUrlSlug($urlSlug);
            
        if(!$category){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Category URL Slug Does Not Exist',
            ));
        }
    
        return new JsonResponse(array(
            'success' => false,
            'message' => 'Category URL Slug Exists',
            'id' => $category->getId(),
            'url_slug' => $category->getUrlSlug(),
        ));
    }
}