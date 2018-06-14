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

/**
 * ProductFeedController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFeedController extends Controller
{
    

    /**
     * @Template("SplicedCommerceBundle:Product:atom_feed.xml.twig")
     * @Route("/products/feed/{page}", name="commerce_product_feed", defaults={"page":"1"})
     */
    public function rssAction($page)
    {
        // getAllProductsFeedQuery

        $products = $this->get('knp_paginator')->paginate(
            $this->get('commerce.product.repository')->getAllProductsFeedQuery(),
            $page,
            $this->get('commerce.configuration')->get('commerce.product.feed.per_page')
        );

        if(!$products->count()){
            $this->get('session')->getFlashBag()->add('error', 'No products were found.');
            return $this->redirect($this->generateUrl('commerce_catalog_feeds'));
        }
        
        return array(
          'page' => $page,    
          'products' => $products,  
        );
    }
}