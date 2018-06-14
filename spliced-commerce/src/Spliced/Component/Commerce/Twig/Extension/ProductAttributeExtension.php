<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Product\ProductAttributeOptionUserDataFormBuilder;

/**
 * 
 */
class ProductAttributeExtension extends \Twig_Extension
{
    /**
     * Constructor
     * 
     */
    public function __construct(ProductAttributeOptionUserDataFormBuilder $userDataFormBuilder)
    {
       $this->userDataFormBuilder = $userDataFormBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_product_attribute_user_data_form' => new \Twig_Function_Method($this, 'getProductAttributeUserDataForm'),
        );
    }

    /**
     * getProductAttributeUserDataForm
     */
    public function getProductAttributeUserDataForm(ProductInterface $product)
    {
        return $this->userDataFormBuilder->buildForm($product)->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute';
    }

}
