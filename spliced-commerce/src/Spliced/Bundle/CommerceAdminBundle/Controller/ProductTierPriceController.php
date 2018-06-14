<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductPriceTierType;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductTierPriceController
 *
 * @Route("/product/{productId}/tier-price")
 */
class ProductTierPriceController extends BaseFilterableController
{
    
    /**
     * @Route("/delete/{tierPriceId}", name="commerce_admin_product_tier_price_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $tierPriceId)
    {
        $em = $this->get('commerce.admin.entity_manager');
        
        $product = $em->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
        
        if (!$product){
            if ($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'product_id' => $productId,
                    'tier_price_id' => $tierPriceId,
                ));
            }
            throw $this->createNotFoundException('Product Not Found');
        }
        
        $tierPrice = null;
        foreach($product->getTierPrices() as $_tierPrice){
            if ($_tierPrice->getId() == $tierPriceId) {
                $tierPrice = $_tierPrice;
            }
        }
        
        if(!$tierPrice){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Tier Price Not Found',
                    'product_id' => $product->getId(),
                    'tier_price_id' => $tierPriceId,
                ));
            }
            throw $this->createNotFoundException('Product Tier Price Not Found');
        }

        $product->removeTierPrice($tierPrice);

        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Tier Price Removed',
                'product_id' => $product->getId(),
                'tier_price_id' => $tierPriceId,
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Tier Price Successfully Removed');
         
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
    
}