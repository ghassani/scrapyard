<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductRelatedProductController
 *
 * @Route("/product/{productId}/related-product")
 */
class ProductRelatedProductController extends Controller
{    
    /**
     * @Route("/delete/{relatedProductId}", name="commerce_admin_product_related_product_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $relatedProductId)
    {
        $product = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
        
        if(!$product) {
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'product_id' => $productId,
                    'related_product_id' => $relatedProductId,
                ));
            }
            throw $this->createNotFoundException('Product Not Found');
        }
        
        $productRelatedProduct = null;
        foreach($product->getRelatedProducts() as $relatedProduct) {
            if ($relatedProduct->getId() == $relatedProductId){
                $productRelatedProduct = $relatedProduct;
            }
        }
        
        if(!$productRelatedProduct){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Related Product Not Found',
                    'product_id' => $product->getId(),
                    'related_product_id' => $relatedProductId,
                ));
            }
            throw new NoResultException('Related Product Not Found');
        }
        
        $product->removeRelatedProduct($productRelatedProduct);
                
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Related Product Successfully Removed',
                'product_id' => $product->getId(),
                'related_product_id' => $relatedProductId,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Related Product Successfully Removed');
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array('id' => $product->getId())));
    }
}
