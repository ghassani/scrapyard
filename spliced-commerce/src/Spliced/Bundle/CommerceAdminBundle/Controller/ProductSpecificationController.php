<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductSpecificationType;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductPriceTierController
 *
 * @Route("/product/{productId}/specification")
 */
class ProductSpecificationController extends BaseFilterableController
{
    
    /**
     * @Route("/delete/{specificationId}", name="commerce_admin_product_specification_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $specificationId)
    {
    	 
        $product = $this->get('commerce.admin.entity_manager')
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
 
        $productSpecification = null;
        foreach($product->getSpecifications() as $specification){
            if ($specification->getId() == $specificationId) {
                $productSpecification = $specification;
            }
        }
        
        if(!$productSpecification){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Specification Not Found',
                    'product_id' => $product->getId(),
                    'specification_option_id' => $specificationId,
                ));
            }
            throw $this->createNotFoundException('Product Specification Not Found');
        }

        $this->get('commerce.admin.entity_manager')->remove($productSpecification);
        $this->get('commerce.admin.entity_manager')->flush();
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'product_id' => $product->getId(),
                'specification_option_id' => $productSpecification->getId(),
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Specification Successfully Deleted');
         
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
    
}