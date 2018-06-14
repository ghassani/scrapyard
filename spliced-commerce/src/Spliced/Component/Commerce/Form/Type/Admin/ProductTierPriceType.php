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
use Spliced\Component\Commerce\Product\ProductPriceHelper;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\ProductInterface;
/**
 * ProductTierPriceType
 * 
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductTierPriceType extends AbstractType
{
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
    }
    
    /**
     * getProduct
     *
     * @return ProductInterface
     */
    protected function getProduct()
    {
        return $this->product;
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
            ->add('minQuantity', 'number', array('required' => true))
            ->add('maxQuantity', 'number', array('required' => true))
            ->add('adjustmentType', 'choice', array(
                'required' => true,
                'empty_value' => '- Adjustment Type -',
                'choices' => array(
                     ProductPriceHelper::ADJUSTMENT_ADD_FIXED_PER_ITEM => 'Add a fixed amount per item',
                     ProductPriceHelper::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM => 'Subtract a fixed amount per item',
                     ProductPriceHelper::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM => 'Add a percentage per item',
                     ProductPriceHelper::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM => 'Subtract a percentage per item',
                     ProductPriceHelper::ADJUSTMENT_FIXED_PER_ITEM => 'Set each item to a specified price',
                ),
            ))
            ->add('adjustment', 'money', array(
                'required' => true, 
                'currency' => 'USD'
            ))
            ->add('options', 'choice', array(
                'required' => false,
                'multiple' => true,
                'choices' => array(
                     'round_to_next_dollar' => 'Round Calculation to Next 0.99th Cent',
                ),
            ))
        ;
    }
   

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_price_tier';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_TIER_PRICE),
        ));
    }
}
