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
use Spliced\Component\Commerce\Model\ProductSpecification;
use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;
use Spliced\Component\Commerce\Form\Extension\ChoiceList\AnyValueChoiceList;
use Spliced\Component\Commerce\Form\DataTransformer;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;

/**
 * ProductSpecificationType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductSpecificationType extends AbstractType
{

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param ProductInterface $product
     * @param ProductSpecification|null $productSpecification
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product, ProductSpecification $productSpecification = null)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
        $this->productSpecification = $productSpecification;
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
     * getProductSpecification
     *
     * @return ProductSpecification|null
     */
    protected function getProductSpecification()
    {
        return $this->productSpecification;
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

        if(!$this->getProductSpecification()){
            $existingSpecifications = array();
            foreach($this->getProduct()->getSpecifications() as $specification){
                if($specification->getOption()){
                    $existingSpecifications[] = $specification->getOption()->getId();
                }
            }

            $builder->add('option', 'entity', array(
                'required' => true,
                'property' => 'name',
                'empty_value' => '- Select a Specification -',
                'class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION),
                'query_builder' => $this->getObjectManager()
                  ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION))
                  ->createQueryBuilder('specification')
            	  ->select('specification')
                  ->where('specification.option NOT IN(:existing)')
            	  ->setParameter('existing', array_unique($existingSpecifications))
            ))->add('value', 'choice', array(
                'required' => false,
                'choice_list' => new AnyValueChoiceList(),
                'multiple' => true,
                'expanded' => false,
                'empty_value' => '- Select a Specification First-',
            ));
            
        } else {
            $specificationOption = $this->getProductSpecification()->getOption();

            if($specificationOption->getOptionType() == 1){

                $builder->add($builder->create('value', 'choice', array(
                    'required' => false,
                    'choices' => array(), //$this->getChoices(),
                    'multiple' => false,
                    'expanded' => false,
                    'empty_value' => '- Select a Value-',

                ))
                  ->addModelTransformer(new DataTransformer\ArrayToStringModelTransformer())
                );
            } else {
                $builder->add('value', 'choice', array(
                    'required' => false,
                    'choices' => array(), //$this->getChoices(),
                    'multiple' => false,
                    'expanded' => false,
                ));
            }
        }
    }
         
    
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_specification';
    }
    

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => $this
             ->getConfigurationManager()
             ->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION),
        ));
    }
    
    /**
     * getChoices
     * 
     * @return array
     */
    protected function getChoices()
    {
    
        $return = array();
         
        if(!$this->getProductSpecification()){
            return $return;
        }

        foreach($this->getProductSpecification()->getOption()->getValues() as $value){
            $return[$value->getId()] = $value->getValue();
        }

        return $return;
    }
}