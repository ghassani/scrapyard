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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;

/**
 * SitemapController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SitemapController extends Controller
{

    const MAX_PER_PAGE = 50000;
    
    /**
     * @Route("/sitemap/xml", name="commerce_sitemap_xml")
     */
    public function xmlAction()
    {
        ini_set('memory_limit', -1);
        
        $products = $this->get('commerce.product.repository')->getAllProductsForSitemap();
        
        $categories = $this->getDoctrine()->getRepository('SplicedCommerceBundle:Category')->getAll();
        
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        return $this->render('SplicedCommerceBundle:Sitemap:sitemap.xml.twig', array(
            'products' => $products,
            'categories' => $categories,
        ),$response);
    }
    

}
