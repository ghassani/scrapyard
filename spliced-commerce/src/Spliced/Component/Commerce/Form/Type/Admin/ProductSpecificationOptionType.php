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
use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductSpecificationOptionType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSpecificationOptionType extends AbstractType
{
    /**
     * Constructor
     * 
     * @param ProductSpecificationOptionInterface $specificationOption, 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ProductSpecificationOptionInterface $specificationOption, ConfigurationManager $configurationManager)
    {
        $this->specificationOption = $specificationOption;
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getSpecificationOption
     * 
     * @return ProductSpecificationOptionInterface
     */
     protected function getSpecificationOption()
     {
         return $this->specificationOption;
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
        $builder->add('name', 'text', array('label' => 'Name', 'required' => true))
        ->add('publicName', 'text', array('label' => 'Label', 'required' => true))
        ->add('description', 'textarea', array('label' => 'Description', 'required' => false))
        ->add('onView', 'choice', array(
            'required' => false,
            'label' => 'On Front',
            'empty_value' => '',
            'choices' => array('1' => 'Yes','0' => 'No'),
            'multiple' => false,
            //'expanded' => true,
        ))
        ->add('onList', 'choice', array(
            'required' => false,
            'label' => 'On List',
            'empty_value' => '',
            'choices' => array('1' => 'Yes','0' => 'No'),
            'multiple' => false,
            //'expanded' => true,
        ))
        ->add('filterable', 'choice', array(
            'required' => false,
            'label' => 'Filterable',
            'empty_value' => '',
            'choices' => array('1' => 'Yes','0' => 'No'),
            'multiple' => false,
            //'expanded' => true,
        ))
        ->add('position', 'number', array('label' => 'Position', 'required' => false));
        
        if(!$this->getSpecificationOption()->getId()){
            $builder->add('key', 'text', array('label' => 'Key', 'required' => true))
              ->add('optionType', 'choice', array(
                'required' => false,
                'label' => 'Type',
                'empty_value' => '',
                'choices' => array(
                     ProductSpecificationOptionInterface::OPTION_TYPE_SINGLE_VALUE         => 'Single Value',
                     ProductSpecificationOptionInterface::OPTION_TYPE_MULTIPLE_VALUE     => 'Multiple Value',
                ),
                'multiple' => false,
            ));
              
              
        } else {
            $builder->add('values', 'collection', array(
                'type' => new ProductSpecificationOptionValueType($this->getConfigurationManager()),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => false,
            ));
        }

    }
   
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_specification_option';
    }
    

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()
                ->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION),
        ));
    }
}