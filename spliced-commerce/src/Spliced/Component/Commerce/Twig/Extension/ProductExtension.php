<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Model\ProductImage;
use Symfony\Component\Filesystem\Filesystem;
use Spliced\Component\Commerce\Media\ImageManager;
use Spliced\Component\Commerce\Repository\ProductRepositoryInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductExtension
 */
class ProductExtension extends \Twig_Extension
{
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager, 
     * @param ImageManager $imageManager
     */
    public function __construct(ConfigurationManager $configurationManager, ImageManager $imageManager )
    {
       $this->configurationManager = $configurationManager;
       $this->imageManager = $imageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_resize_product_image' => new \Twig_Function_Method($this, 'resizeImage'),
            'commerce_product_by_id' => new \Twig_Function_Method($this, 'getProductById'),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {

    	return array(
    		// specification type constants
    		'COMMERCE_SPECIFICATION_OPTION_TYPE_SINGLE_VALUE' => ProductSpecificationOptionInterface::OPTION_TYPE_SINGLE_VALUE,
    		'COMMERCE_SPECIFICATION_OPTION_TYPE_MULTIPLE_VALUE' => ProductSpecificationOptionInterface::OPTION_TYPE_MULTIPLE_VALUE,
    		'COMMERCE_SPECIFICATION_OPTION_TYPE_CUSTOM_VALUE' => ProductSpecificationOptionInterface::OPTION_TYPE_CUSTOM_VALUES,
    		
    		// attribute type constants
    		'COMMERCE_ATTRIBUTE_OPTION_TYPE_USER_DATA_INPUT' => ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT,
    		'COMMERCE_ATTRIBUTE_OPTION_TYPE_USER_DATA_SELECTION' => ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION,
    			
    	);
    }

    /**
     *
     * @param int $productId
     */
    public function getProductById($productId)
    {
        try{
            $product = $this->getConfigurationManager()
              ->getDocumentManager()
              ->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
              ->findOneById($productId);
            return $product;
        }catch(NoResultException $e){
            return false;
        }
    }
    
    /**
     *
     * @param ProductImage $image
     * @param int   $width
     * @param int   $height
     */
    public function resizeImage(ProductImage $image, $width, $height)
    {
        return $this->imageManager->resizeProductImage($image, $width, $height, array('quality' => 100));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_product';
    }

}
