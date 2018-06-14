<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductUpsaleController
 *
 * @Route("/product/{productId}/upsale")
 */
class ProductUpsaleController extends Controller
{    
    /**
     * @Route("/delete/{upsaleId}", name="commerce_admin_product_upsale_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $upsaleId)
    {
        $product = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
        
        if(!$product) {
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'product_id' => $productId,
                    'upsale_id' => $upsaleId,
                ));
            }
            throw $this->createNotFoundException('Product Not Found');
        }
        
        $productUpsale = null;
        foreach($product->getUpsales() as $upsale) {
            if ($upsale->getId() == $upsaleId){
                $productUpsale = $upsale;
            }
        }
        
        if(!$productUpsale){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Upsale Not Found',
                    'product_id' => $product->getId(),
                    'upsale_id' => $upsaleId,
                ));
            }
            throw new NoResultException('Product Upsale Not Found');
        }
        
        $product->removeUpsale($productUpsale);
                
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Product Upsale Successfully Removed',
                'product_id' => $product->getId(),
                'upsale_id' => $upsaleId,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Upsale Successfully Removed');
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array('id' => $product->getId())));
    }
}
