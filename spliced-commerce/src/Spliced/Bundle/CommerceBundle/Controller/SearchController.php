<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Product\ProductAttributeFilterOrganizer;
use Spliced\Bundle\CommerceBundle\Form\Type\AdvancedSearchFormType;

/**
 * SearchController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SearchController extends Controller
{
    /**
     * @Route("/catalog/search", name="commerce_search")
     * @Template("SplicedCommerceBundle:Search:results.html.twig")
     */
    public function searchAction()
    {
        $queryString = trim($this->getRequest()->query->get('q'));
                
        if(empty($queryString)) {
            $this->get('session')->getFlashBag()->add('error','You must enter a search term');
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        // load products
        $paginator = $this->get('knp_paginator')->paginate(
            $this->get('commerce.product.repository.solr')->getSearchQuery($queryString, array('id')),
            $this->getRequest()->query->get('page'),
            $this->get('commerce.product.filter_manager')->getPerPage()
        );
        
        $productIds = array();
        foreach ($paginator as $item) {
            $productIds[] = $item['id'];
        }
        
        $products = $this->get('commerce.product.repository')->findByIds($productIds);
        
        $attributeFilterQuery = $this->get('commerce.product.repository')
          ->getProductsForSearchSolrQuery($queryString, array('id','attributeData'))
          ->setRows($paginator->getTotalItemCount());
        
        $attributeFilter = new ProductAttributeFilterOrganizer($this->get('solarium.client')->select($attributeFilterQuery));
        
        
        $this->get('event_dispatcher')->dispatch(Events\Event::EVENT_SEARCH, new Events\SearchEvent($queryString));
        
        return array(
            'paginator' => $paginator,
            'products' =>  $products,
            'queryString' =>  $queryString,
            'attributeFilter' => $attributeFilter,
            'productFilterManager' => $this->get('commerce.product.filter_manager'),
        );
    }
    
    /**
     * @Route("/catalog/advanced-search", name="commerce_advanced_search")
     * @Template("SplicedCommerceBundle:Search:advanced.html.twig")
     */
    public function advancedSearchAction()
    {
        $form = $this->createForm(new AdvancedSearchFormType());
        
        return array('form' => $form->createView());
    }
}