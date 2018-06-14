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
use Doctrine\ORM\NoResultException;

/**
 * PageController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PageController extends Controller
{
    /**
     * @Route("/page/view/{id}", name="commerce_content_page_view")
     * @Template("SplicedCommerceBundle:Page:view.html.twig")
     */
    public function viewAction($id)
    {

        $page = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:ContentPage')
          ->findOneById($id);
        
        if(!$page) {
            throw $this->createNotFoundException("Page Not Found");
        }

        $this->get('commerce.breadcrumb')->createBreadcrumb(
            $page->getTitle(),
            $page->getTitle(),
            $page->getUrlSlug(),
            null,
            true
        );

        return array(
            'layout' => $page->getPageLayout() ? 
                $page->getPageLayout() : $this->get('commerce.configuration')->get('commerce.cms.default_layout'),
            'page' => $page,
        );
    }
    
    /**
     * @Route("/page/{slug}", name="commerce_content_page_view_by_slug")
     * @Template("SplicedCommerceBundle:Page:view.html.twig")
     */
    public function viewBySlugAction($slug)
    {
    
        $page = $this->get('commerce.document_manager')
          ->getRepository('SplicedCommerceBundle:ContentPage')
          ->findOneByUrlSlug($slug);
    
        if(!$page) {
            throw $this->createNotFoundException("Page Not Found");
        }
    
        $this->get('commerce.breadcrumb')->createBreadcrumb(
            $page->getTitle(),
            $page->getTitle(),
            $page->getUrlSlug(),
            null,
            true
        );
    
        return array(
             'layout' => $page->getPageLayout() ?
             $page->getPageLayout() : $this->get('commerce.configuration')->get('commerce.cms.default_layout'),
             'page' => $page,
        );
    }
}
