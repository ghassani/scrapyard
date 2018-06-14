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
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductController extends Controller
{

    /**
     * @Template("SplicedCommerceBundle:Product:view.html.twig")
     * @Route("/product/view/{id}", name="commerce_product_view")
     * 
     * Loads the view for a single product
     *
     * @param $id - Product ID to Load
     */
    public function viewAction($id)
    {
        $productRepository = $this->get('commerce.entity_manager')
          ->getRepository('SplicedCommerceBundle:Product');

        // load product
        $product = $productRepository->findOneById($id);
            
        if(!$product){
            throw $this->createNotFoundException('Product Not Found');
        }
    
        return $this->renderProduct($product);
    }
    
    /**
     * @Template("SplicedCommerceBundle:Product:view.html.twig")
     * @Route("/product/{slug}", name="commerce_product_view_by_slug")
     *
     * Loads the view for a single product
     *
     * @param $id - Product ID to Load
     */
    public function viewBySlugAction($slug)
    {
        $productRepository = $this->get('commerce.entity_manager')
          ->getRepository('SplicedCommerceBundle:Product');
    
        // load product
        $product = $productRepository->findOneByUrlSlug($slug);
    
        if(!$product){
            throw $this->createNotFoundException('Product Not Found');
        }
    
    
        return $this->renderProduct($product);
    }
    
    /**
     * 
     */
    protected function renderProduct(ProductInterface $product)
    {
        // set crumbs
        $this->get('commerce.breadcrumb')
        ->createBreadcrumb('Catalog', 'Catalog', $this->generateUrl('catalog_index'), null, true)
        ->createBreadcrumb($product->getName(), $product->getName(), $product->getUrlSlug(), null, true);
        
        // dispatch a product viewed event
        $this->get('event_dispatcher')->dispatch(
        	Events\Event::EVENT_PRODUCT_VIEW,
            new Events\ProductViewEvent($product)
        );
        
        return array(
            'product' => $product,
            'quantity' => $this->getRequest()->query->get('q', 1),
        );
    }
}
