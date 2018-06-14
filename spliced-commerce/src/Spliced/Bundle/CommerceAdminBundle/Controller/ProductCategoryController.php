<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductCategoryType;
use Spliced\Bundle\CommerceAdminBundle\Entity\ProductCategory;

/**
 * ProductCategory controller.
 *
 * @Route("/product/{productId}/category")
 */
class ProductCategoryController extends Controller
{

    /**
     * @Route("/add", name="commerce_admin_product_category_add")
     * @Method({"POST","GET"})
     * @Template("SplicedCommerceAdminBundle:ProductCategory:add.html.twig")
     */
    public function addAction($productId)
    {
        $categories = $this->getCategories();
        
        $product = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
            
        if(!$product){
            throw $this->createNotFoundException('Product Not Found');            
        }
                
 
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Add Category To Product',
                'modal' => $this->render('SplicedCommerceAdminBundle:ProductCategory:add_modal.html.twig',array(
                     'product' => $product,
                    'categories' => $categories,
                ))->getContent(),
            ));
        }
        
        return array(
            'product' => $product,
            'categories' => $categories,
        );
    }

    /**
     * @Route("/save/", name="commerce_admin_product_category_save")
     * @Method({"POST"})
     * @Template("SplicedCommerceAdminBundle:ProductCategory:add.html.twig")
     */
    public function saveAction($productId)
    {
        $categories = $this->getCategories();
        
        $product = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
            
        if(!$product){
            throw $this->createNotFoundException('Product Not Found');            
        }
                
        
        if($this->getRequest()->request->has('categories')){
            $addedCategories = array();
            foreach($this->getRequest()->request->get('categories') as $categoryId){
                $category = $this->get('commerce.admin.document_manager')
                    ->getRepository('SplicedCommerceAdminBundle:Category')
                  ->findOneById($categoryId);
                
                if($category && !$product->hasCategory($category)){
                    
                    $product->addCategory($category);
                      
                    $this->get('event_dispatcher')->dispatch(
                        Events\Event::EVENT_PRODUCT_CATEGORY_ADD,
                        new Events\ProductCategoryAddEvent($product, $category)
                    );
                    
                    $addedCategories[] = $category;
                }
            }
            
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => true,
                    'html' => $this->render('SplicedCommerceAdminBundle:ProductCategory:category_row.html.twig',array(
                        'categories' => $addedCategories,
                        'product' => $product,
                    ))->getContent(),
                ));
            }
            
            $this->get('session')->getFlashBag()->add('success', sprintf('%s Categories Successfully Related', count($addedCategories)));
            
            return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
                'id' => $product->getId(),
            )));
        }
        

        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => false,
                'html' => $this->render('SplicedCommerceAdminBundle:ProductCategory:add_modal_content.html.twig',array(
                    'categories' => $categories,
                    'product' => $product,
                ))->getContent(),
            ));
        }
        
        return array(
            'product' => $product,
            'categories' => $categories, 
        ); 
    }
    
    /**
     * @Route("/delete/{productCategoryId}", name="commerce_admin_product_category_delete")
     * @Method({"POST","GET"})
     */
    public function deleteAction($productId, $productCategoryId)
    {
        try{
            $product = $this->get('commerce.product.repository')->findOneById($productId);
                
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Product Not Found');
        }
        
        $productCategory = $this->getDoctrine()
            ->getRepository('SplicedCommerceAdminBundle:ProductCategory')
            ->findOneById($productCategoryId);
        
        if(!$productCategory){
            throw $this->createNotFoundException('Product Category Not Found');
        }
        
        if($productCategory->getProduct()->getId() != $product->getId()){
            throw $this->createNotFoundException('Product Category Not Associated With Specified Product');
        }
        
        $categoryId = $productCategory->getId();
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_CATEGORY_DELETE,
            new Events\ProductCategoryDeleteEvent($product, $productCategory)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'category_id' => $categoryId,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Category Successfully Unrelated');
        
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
    
    /**
     * getCategories
     *
     * @return Collection
     */
    private function getCategories()
    {
        return $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Category')
          ->getRoot();
    }
    
    
}
