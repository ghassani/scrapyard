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
use Spliced\Component\Commerce\Form\DataTransformer\ProductTypeaheadTransformer;
use Spliced\Component\Commerce\Helper\Product\Price as PriceHelper;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ProductBundledItemType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductBundledItemType extends AbstractType
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
        return $this->getConfigurationManager()->getEntityManager();
    }
    
    /**
     * getProductRepository
     * 
     * @return ProductRepository
     */
     protected function getProductRepository()
     {
         return $this->getObjectManager()
           ->getRepository(
               $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT)
           );
     }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('product', 'text', array('required' => true))
              ->addModelTransformer(new ProductTypeaheadTransformer($this->getProductRepository()))
        )
        ->add('quantity', 'number', array('required' => true))
        ->add('allowTierPricing', 'checkbox', array('required' => false, 'value' => true))
        ->add('priceAdjustment', 'money', array(
            'label' => 'Price Adjustment', 
            'required' => false,
            'currency' => 'USD',
            'precision' => 2,
        ))
        ->add('priceAdjustmentType', 'choice', array(
            'label' => 'Price Adjustment Type', 
            'required' => false,
            'empty_value' => '- Adjustment Type -',
            'choices' => array(
                PriceHelper::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM => 'Subtract a Percentage',
                PriceHelper::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM => 'Add a Percentage',
                PriceHelper::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM => 'Subtract Fixed Amount',
                PriceHelper::ADJUSTMENT_ADD_FIXED_PER_ITEM => 'Add Fixed Amount',
                PriceHelper::ADJUSTMENT_FIXED_PER_ITEM => 'Set Product To Specified Price',
            ),
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_bundled_item';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_BUNDLED_ITEM),
        ));
    }
}