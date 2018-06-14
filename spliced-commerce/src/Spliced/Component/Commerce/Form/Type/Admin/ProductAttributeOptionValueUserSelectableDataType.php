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
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
 
/**
 * ProductAttributeOptionValueUserSelectableDataType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValueUserSelectableDataType extends AbstractType
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
        $builder->add('products_to_add', 'collection', array(
            'type'         => new ProductAttributeOptionValueProductType($this->getConfigurationManager()),
            'allow_add'    => true,
            'by_reference' => false, 
            'prototype_name' => '__product_name__',
        ));
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
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option_value_data';
    }
}