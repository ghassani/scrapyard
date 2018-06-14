<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductFilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.product';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductFilterType';
    
    /**
     * Lists all Product entities.
     *
     * @Route("/", name="commerce_admin_product")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        
        // load products
        $products = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
                ->getRepository('SplicedCommerceAdminBundle:Product')->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
        
        $filterForm = $this->createForm(new ProductFilterType(), $this->getFilters());

        return array(
            'products' => $products,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Product entity.
     *
     * @Route("/new", name="commerce_admin_product_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {


        $form = $this->get('commerce.admin.form_factory')->createProductForm();

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Saves a new Product entity.
     *
     * @Route("/save", name="commerce_admin_product_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Product:new.html.twig")
     */
    public function saveAction(Request $request)
    {

        $form = $this->get('commerce.admin.form_factory')->createProductForm();
        
        if ($form->bind($request) && $form->isValid()) {
            
            $product = $form->getData();
    
            $this->get('commerce.product_manager')->save($product);
            
            $this->get('session')->getFlashBag()->add('success', 'Product Successfully Created. Continue adding details.');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
                'id' => $product->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your data.');
        
    
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Displays a form to edit an existing Product entity by SKU.
     *
     * @Route("/{sku}/edit-sku", name="commerce_admin_product_edit_by_sku", requirements={"sku" = ".+"})
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:Product:edit.html.twig")
     */
    public function editBySkuAction($sku)
    {
        try{
            $product = $this->get('commerce.object_manager')->getRepository('SplicedCommerceAdminBundle:Product')
              ->findOneBySku($sku);
             
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Product.');
        }
        
        $form = $this->createForm(new ProductType(), $product);
        
        return array(
            'product'     => $product,
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
            'affiliates'  => $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Affiliate')->getAll(),
            'orders' => $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Order')->findByOrderedSku($product->getSku()),
        );
    }
    
    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", name="commerce_admin_product_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {

        $product = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($id);
        
        if(!$product){
            throw $this->createNotFoundException('Unable to find Product.');
        }

        $form = $this->get('commerce.admin.form_factory')->createProductForm($product);
    
        return array(
            'product'     => $product,
            'form'   => $form->createView(),
            'delete_form' => $this->createDeleteForm($id)->createView(),
            'orders' => $this->getDoctrine()->getRepository('SplicedCommerceAdminBundle:Order')
        		->findByOrderedSku($product->getSku()),
        );
    }

    /**
     * Updates an existing Product entity.
     *
     * @Route("/{id}/update", name="commerce_admin_product_update")
     * @Method({"PUT","POST"})
     * @Template("SplicedCommerceAdminBundle:Product:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        
        $product = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($id);

        if(!$product){
            throw $this->createNotFoundException('Unable to find Product.');
        }
        
        
        $form = $this->get('commerce.admin.form_factory')->createProductForm($product);
        
        $deleteForm = $this->createDeleteForm($id);
        
        if($form->bind($this->getRequest()) && $form->isValid()) {
            $product = $form->getData();

            $this->get('commerce.product_manager')->update($product);                        
            
            $this->get('session')->getFlashBag()->add('success', 'Product Successfully Updated');
            
            return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
                'id' => $product->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your data');
        
        return array(
            'product'     => $product,
            'form'         => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'orders'      => array(),
        );
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}/delete", name="commerce_admin_product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        if($form->bind($request) && $form->isValid()) {
            $product = $this->get('commerce.admin.entity_manager')
              ->getRepository('SplicedCommerceAdminBundle:Product')
              ->findOneById($id);

            if (!$product) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }
            $this->get('event_dispatcher')->dispatch(
                Events\Event::EVENT_PRODUCT_DELETE,
                new Events\ProductDeleteEvent($product)
            );
        }

        return $this->redirect($this->generateUrl('commerce_admin_product'));
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
     * @Route("/filter", name="commerce_admin_product_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
        return $this->redirect($this->generateUrl('commerce_admin_product'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_product_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
        return $this->redirect($this->generateUrl('commerce_admin_product'));
    }


    /**
     * Deletes a Product entity.
     *
     * @Route("/batch", name="commerce_admin_product_batch")
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
    

    /**
    * batchDelete
    * 
    * @param array $ids
    */
    protected function batchDelete(array $ids)
    {
        $entities = $em->getRepository('SplicedCommerceAdminBundle:Product')->findById($id);
        
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
        
        return $this->redirect($this->generateUrl('product'));
    }
    
    /**
     * Check Sku
     *
     * @Route("/check-sku", name="commerce_admin_product_check_sku")
     * @Template()
     */
    public function checkSkuAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Invalid Request Type');
        } else if(!$this->getRequest()->request->has('sku')){
            throw new \InvalidArgumentException('SKU POST variable required to check sku');
        }
    
        $product = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneBySku($this->getRequest()->request->get('sku'));
                
        if(!$product){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Sku Does Not Exist',
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'message' => 'Sku Exists',
            'id' => $product->getId()
        ));
    }
    
    /**
     * Searches for Products by ID, Sku, or Description and returns
     * a JSON array 
     *
     * @Route("/ajax-search", name="commerce_admin_product_ajax_search")
     * @Template()
     */
    public function ajaxSearchAction()
    {
        $query = $this->getRequest()->query->get('q', $this->getRequest()->request->get('q'));
    
        
        $productsQuery = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->createQueryBuilder('product')
          ->select('product, images')
          ->where('REGEXP(product.name, :nameExp) = 1 OR REGEXP(product.sku, :skuExp) = 1')
          ->leftJoin('product.images', 'images')
          ->setParameter('nameExp', $query)
          ->setParameter('skuExp', $query);
          
        $products = $productsQuery
          ->getQuery()
          ->setMaxResults(30)
          ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        foreach($products as &$product){
            $product['value'] = sprintf('%s:%s - %s', $product['id'], $product['name'], $product['sku']);
            if(count($product['images'])){
                try{
                	$product['thumbnail'] = $this->get('commerce.image_manager')->resizeProductImage($product['images'][0], 100, 100);
                } catch(\Exception $e){ /* DO NOTHING */ }
            }
        } 

        return new JsonResponse($products);
    }
    
    /**
     * checkUrlSlugAction
     *
     * @Route("/check-url-slug", name="commerce_admin_product_check_slug")
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
    
        $product = $this->get('commerce.admin.entity_manager')
        ->getRepository('SplicedCommerceAdminBundle:Product')
        ->findOneByUrlSlug($urlSlug);
    
        if(!$product){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Product URL Slug Does Not Exist',
            ));
        }
    
        return new JsonResponse(array(
            'success' => false,
            'message' => 'Product URL Slug Exists',
            'id' => $product->getId(),
            'url_slug' => $product->getUrlSlug(),
        ));
    }
}