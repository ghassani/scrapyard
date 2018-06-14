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
 * FeedController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FeedController extends Controller
{

    /**
     * @Template("SplicedCommerceBundle:Feed:index.html.twig")
     * @Route("/catalog/feeds", name="commerce_catalog_feeds")
     */
    public function indexAction()
    {

        $this->get('commerce.breadcrumb')
          ->addBreadcrumb('Catalog', 'Catalog', $this->generateUrl('catalog_index'))
          ->addBreadcrumb('Feeds', 'Feeds', $this->generateUrl('commerce_catalog_feeds'));
        
        $totalProducts = $this->get('commerce.product.repository')->getProductCount();
        
        $productFeedPages = floor($totalProducts/$this->get('commerce.configuration')->get('commerce.product.feed.per_page'));
        
        
        return array( 
            'totalProducts' => $totalProducts,
            'productFeedPages' => $productFeedPages,
        );
    }
}