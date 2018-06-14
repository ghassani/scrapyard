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
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Product\ProductPriceHelper;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Form\DataTransformer\ProductTypeaheadTransformer;

/**
 * ProductAttributeOptionValueProductType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValueProductType extends AbstractType
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
     * getObjectManager
     *
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
     
    /**
     * getProductRepository
     *
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->getObjectManager()
        ->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT));
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $typeaheadTransformer = new ProductTypeaheadTransformer($this->getProductRepository());
        
        $builder->add($builder->create('product', 'text', array('required' => true))
                ->addModelTransformer($typeaheadTransformer));
        
        $builder->add('priceAdjustment', 'money', array(
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
                ProductPriceHelper::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM => 'Subtract a Percentage',
                ProductPriceHelper::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM => 'Add a Percentage',
                ProductPriceHelper::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM => 'Subtract Fixed Amount',
                ProductPriceHelper::ADJUSTMENT_ADD_FIXED_PER_ITEM => 'Add Fixed Amount',
                ProductPriceHelper::ADJUSTMENT_FIXED_PER_ITEM => 'Set Product To Specified Price',
            ),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION_VALUE_PRODUCT),
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option_value_product';
    }
}