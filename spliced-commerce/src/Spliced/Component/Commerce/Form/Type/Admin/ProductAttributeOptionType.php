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
 * ProductAttributeOptionType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionType extends AbstractType
{
    /**
     * Constructor
     * 
     * @param ProductAttributeOptionInterface $attributeOption, 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ProductAttributeOptionInterface $attributeOption, ConfigurationManager $configurationManager)
    {
        $this->attributeOption = $attributeOption;
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getAttributeOption
     * 
     * @return ProductAttributeOptionInterface
     */
     protected function getAttributeOption()
     {
         return $this->attributeOption;
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
        ->add('publicLabel', 'text', array('label' => 'Label', 'required' => true))
        ->add('description', 'textarea', array('label' => 'Description', 'required' => false))
        ->add('position', 'number', array('label' => 'Position', 'required' => false));
        
        if(!$this->getAttributeOption()->getId()){
            $builder->add('key', 'text', array('label' => 'Key', 'required' => true))
              ->add('optionType', 'choice', array(
                'required' => false,
                'label' => 'Type',
                'empty_value' => '',
                'choices' => array(
                    ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT => 'User Input',
                    ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION => 'User Selectable (Price Alterable)',
                ),
                'multiple' => false,
                //'expanded' => true,
            ));
        }  else {
            
            
            if($this->getAttributeOption()->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT){
                $builder->add('values', 'collection', array(
                    'type' => new ProductAttributeOptionValueType($this->getConfigurationManager()),
                     'allow_add' => true,
                     'by_reference' => false,
                     'allow_delete' => false,
                ))
                ->add('optionData', new ProductAttributeOptionUserDataInputType());
                
            } else if($this->getAttributeOption()->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION){
                
                $builder->add('values', 'collection', array(
                    'type' => new ProductAttributeOptionValueUserSelectableType($this->getConfigurationManager()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                ))
                ->add('optionData', new ProductAttributeOptionUserDataSelectionType());
            }
        }

    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option';
    }
    

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION),
        ));
    }
}