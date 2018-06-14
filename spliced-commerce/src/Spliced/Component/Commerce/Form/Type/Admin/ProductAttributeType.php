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
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductAttribute;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Form\Extension\ChoiceList\AnyValueChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;

/**
 * ProductAttributeType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductAttributeType extends AbstractType
{

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product, ProductAttribute $productAttribute = null)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
        $this->productAttribute = $productAttribute;
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
     * getProductAttribute
     *
     * @return ProductAttribute
     */
    protected function getProductAttribute()
    {
        return $this->productAttribute;
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
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        if(!$this->getProductAttribute()){
            $existingAttributes = array();
            foreach($this->getProduct()->getAttributes() as $attribute){
                if($attribute->getOption()){
                    $existingAttributes[] = $attribute->getOption()->getKey();
                }
            }
            
            $existingAttributes = array_unique($existingAttributes);
            
            $builder->add('option', 'document', array(
                'required' => true,
                'property' => 'name',
                'empty_value' => '- Select an Attribute -',
                'class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION),
                'query_builder' => $this->getObjectManager()
                  ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION))
                  ->createQueryBuilder()            
                  ->field('key')->notIn($existingAttributes)
            ))->add('values', 'choice', array(
                'required' => false,
                'choice_list' => new AnyValueChoiceList(),
                'multiple' => true,
                'expanded' => false,
                'empty_value' => '- Select an Attribute First-',
            ));
        }
    }
        
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute';
    }
    

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE),
        ));
    }
    
    /**
     * 
     * @return array
     */
    protected function getChoices()
    {
    
        $return = array();
         
        if(!$this->getProductAttribute()){
            return $return;
        }
        
        foreach($this->getProductAttribute()->getOption()->getValues() as $value){
            $return[$value->getValue()] = $value->getValue();
        }
    
        return $return;
    }
}