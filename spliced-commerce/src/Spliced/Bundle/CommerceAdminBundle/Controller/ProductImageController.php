<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\HttpFoundation\AjaxResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\ProductImageType;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductImageController
 *
 * @Route("/product/{productId}/image")
 */
class ProductImageController extends BaseFilterableController
{
    /**
     * @Route("/delete/{imageId}", name="commerce_admin_product_image_delete")
     * @Method({"GET","POST"})
     */
    public function deleteAction($productId, $imageId)
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
 
        $productImage = null;
        foreach($product->getImages() as $image){
            if ($image->getId() == $imageId) {
                $productImage = $image;
            }
        }
        
        if(!$productImage){
            if($this->getRequest()->isXmlHttpRequest()){
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Product Image Not Found',
                    'product_id' => $product->getId(),
                    'image_id' => $imageId,
                ));
            }
            throw $this->createNotFoundException('Product Image Not Found');
        }
        
        $this->get('event_dispatcher')->dispatch(
            Events\Event::EVENT_PRODUCT_IMAGE_DELETE,
            new Events\ProductImageDeleteEvent($product, $productImage)
        );
        
        if($this->getRequest()->isXmlHttpRequest()){
            return new JsonResponse(array(
                'success' => true,
                'product_id' => $product->getId(),
                'image_id' => $productImage->getId(),
            ));
        }
        
        $this->get('session')->getFlashBag()->add('success', 'Product Image Successfully Deleted');
         
        return $this->redirect($this->generateUrl('commerce_admin_product_edit', array(
            'id' => $product->getId(),
        )));
    }
}