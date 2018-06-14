<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionValueInterface;
use Spliced\Component\Commerce\Helper\Product\Price as PriceHelper;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductAttributeOptionValueUserSelectableType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValueUserSelectableType extends AbstractType
{
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
     
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    
        $builder
        ->add('value', 'text', array('label' => 'Value', 'required' => true))
        ->add('publicValue', 'text', array('label' => 'Public Value', 'required' => true))
        ->add('position', 'number', array('label' => 'Position', 'required' => false))
        ->add('priceAdjustment', 'money', array(
            'label' => 'Price Adjustment', 
            'required' => false,
            'currency' => 'USD',
            'precision' => 2,
        ))
        ->add('priceAdjustmentType', 'choice', array(
            'label' => 'Price Adjustment Type', 
            'required' => false,
            'empty_value' => '',
            'choices' => array(
                PriceHelper::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM => 'Subtract a Percentage',
                PriceHelper::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM => 'Add a Percentage',
                PriceHelper::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM => 'Subtract Fixed Amount',
                PriceHelper::ADJUSTMENT_ADD_FIXED_PER_ITEM => 'Add Fixed Amount',
                PriceHelper::ADJUSTMENT_FIXED_PER_ITEM => 'Set Product To Specified Price',
            ),
        ))->add('products', 'collection', array(
            'type' => new ProductAttributeOptionValueProductType($this->getConfigurationManager()),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype_name' => '__product_name__',
        ));        
    }
    
    /** 
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option_value';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION_VALUE),
        ));
    }
}