<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductAttributeType;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductPriceTierController
 *
 * @Route("/product/{productId}/attribute")
 */
class ProductAttributeController extends BaseFilterableController
{
    
    /**
     * @Route("/delete/{attributeId}", name="commerce_admin_product_attribute_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $attributeId)
    {
        $product = $this->get('commerce.admin.document_manager')
          ->getRepository('SplicedCommerceAdminBundle:Product')
          ->findOneById($productId);
        
        if(!$product){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Not Found',
                    'product_id' => $productId,
                    'image_id' => $imageId,
                ));
            }
            throw $this->createNotFoundException('Product Not Found');
        }
 
        $productAttribute = null;
        foreach($product->getAttributes() as $attribute){
            if ($attribute->getId() == $attributeId) {
                $productAttribute = $attribute;
            }
        }
        
        if(!$attribute){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Attribute Not Found',
                    'product_id' => $product->getId(),
                    'image_id' => $attributeId,
                ));
            }
            throw $this->createNotFoundException('Product Attribute Not Found');
        }

        $product->removeAttribute($attribute);
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'product_id' => $product->getId(),
                'attribute_id' => $attribute->getId(),
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Attribute Successfully Deleted');
         
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
    
}