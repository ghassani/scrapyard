<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Entity\Product;
use Spliced\Bundle\CommerceAdminBundle\Entity\ProductAttribute;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductFilterType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductBatchPriceFilterType;
use Doctrine\ORM\Query;

/**
 * ProductUtilityController controller.
 *
 * @Route("/product/utility")
 */
class ProductUtilityController extends Controller
{
    const FILTER_TAG = 'filter.product.utility';
    
    /**
     * Batch Price Update Utility
     *
     * @Route("/batch-price-update", name="commerce_admin_product_utility_price_batch_update")
     * @Method("GET")
     * @Template("SplicedCommerceAdminBundle:ProductUtility:price_batch_update.html.twig")
     */
    public function priceBatchUpdateAction()
    {
            
        $currentFilters = $this->getFilters('price_batch_update');
        
        $filterForm = $this->createForm(
            new ProductFilterType(), 
            $currentFilters instanceof ListFilter ? $currentFilters : new ListFilter($currentFilters)
        );
         
        $productsQuery = $this->getDoctrine()
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->getAdminListQuery($filterForm->getData(), false);
        /*
        $productsQuery->addSelect('productAttributes, productAttributesValue, productAttributesOption')
             ->leftJoin('product.attributes','productAttributes', 'WITH', 'productAttributes.option = 5')
             ->leftJoin('productAttributes.option','productAttributesOption', null, null, 'productAttributesOption.name')
             ->leftJoin('productAttributes.value','productAttributesValue', null, null, 'productAttributesValue.value');
        */
        $products = $productsQuery
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getResult();
        
        
        
        /*
        echo '<pre>';
        print_r($products);
        die();*/
        
        return array(
            'filterForm' => $filterForm->createView(),
            'products' => $products,
        );
    }
    
    /**
     * Lists all Product entities.
     *
     * @Route("/batch-price-update/save", name="commerce_admin_product_utility_price_batch_update_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:ProductUtility:price_batch_update.html.twig")
     */
    public function priceBatchUpdateSaveAction()
    {
        if(!$this->getRequest()->request->has('products')) {
            $this->get('session')->getFlashBag()->add('error', 'Invalid Request');
            return $this->redirect($this->generateUrl('commerce_admin_product_price_batch_update'));
        }
                        
        $postData = $this->getRequest()->request->get('products');
        $ids = array();
        array_walk($postData, function($value, $key) use(&$ids){
            $ids[] = $key;
        });

        $productsQuery = $this->getDoctrine()
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->getAdminListQuery(null, false)
           ->where('product.id IN (:ids)')
           ->setParameter('ids', $ids);
            
         $products = $productsQuery
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getResult();

        $count = 0;
        foreach($products as $product){
            if(isset($postData[$product->getId()])){
                $cost = $postData[$product->getId()]['cost'];
                $price = $postData[$product->getId()]['price'];
                $status = $postData[$product->getId()]['status'];
                $isActive = $postData[$product->getId()]['isActive'];
                
                $product->setCost($cost)
                 ->setPrice($price)
                 ->setStatus($status)
                 ->setIsActive($isActive);

                $this->getDoctrine()->getManager()->persist($product);
                $count++;
            }
        }
        
        $this->getDoctrine()->getManager()->flush();
        
        $this->get('session')->getFlashBag()->add('success', sprintf('Updated %s Products', $count));
        return $this->redirect($this->generateUrl('commerce_admin_product_price_batch_update'));
    }
    

    /**
     * Filters the list of entities for Product entity.
     *
     * @Route("/filter", name="commerce_admin_product_utility_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $request = $this->get('request');
    
        $form   = $this->createForm(new ProductFilterType());
        $filters = array();
    
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
    
            $this->setFilters($filters, $this->getRequest()->query->get('tag'));
        }
    
    
        $this->get('session')->getFlashBag()->add('notice', 'Product Filters Updated');
    
        return $this->redirect($request->headers->has('referer') ?
                $request->headers->get('referer') : $this->generateUrl('commerce_admin_product'));
    }
    
    
    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_product_utility_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->setFilters($filters, $this->getRequest()->query->get('tag'));
        $this->get('session')->getFlashBag()->add('notice', 'Product Filters Cleared');
        return $this->redirect($this->getRequest()->headers->has('referer') ?
                $this->getRequest()->headers->get('referer') : $this->generateUrl('commerce_admin_product'));
    }
    
    
    /**
     * Gets the currently applied filters
     *
     * @return array
     */
    private function getFilters($tag = null)
    {
        $tag = static::FILTER_TAG.( $tag ? '.'.$tag : null);
        return $this->get('session')->has($tag) ?
        unserialize($this->get('session')->get($tag)) : new ListFilter();
    }
     
    /**
     * Gets the currently applied filters
     *
     * @return array
     */
    private function setFilters(ListFilter $filters, $tag = null)
    {
        $tag = static::FILTER_TAG.( $tag ? '.'.$tag : null);
        $this->get('session')->set($tag, $filters->serialize());
        return $this;
    }
    
}
