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
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Product\ProductTypeManager;
use Spliced\Component\Commerce\Repository\ProductRepository;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Form\Field\WeightType;
use Spliced\Component\Commerce\Form\Field\DimensionsType;
use Doctrine\Common\Persistence\ObjectManager;
use Spliced\Component\Commerce\Form\Field\ProductAttributeCollectionType;
use Spliced\Component\Commerce\Form\Field\ProductSpecificationCollectionType;

/**
 * ProductType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductType extends AbstractType
{
    /** @var ProductInterface */
    protected $product;
    
    /** @var ConfigurationManager */
    protected $configurationManager;
    
    /** @var ProductTypeManager */
    protected $typeManager;
    
    /** @var ObjectManager */
    protected $om;
    
    /**
     * Constructor
     * 
     * @param ProductInterface $product
     * @param ConfigurationManager $configurationManager
     * @param ProductTypeManager $typeManager
     * @param ObjectManager $om
     */
    public function __construct(ProductInterface $product, ConfigurationManager $configurationManager, ProductTypeManager $typeManager, ObjectManager $om)
    {
        $this->product = $product;
        $this->typeManager = $typeManager;
        $this->configurationManager = $configurationManager;
        $this->om = $om;
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
     * getObjectManager
     * 
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->om;
    }
    
    /**
     * getTypeManager
     * 
     * @return ProductTypeManager
     */
    protected function getTypeManager()
    {
        return $this->typeManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', 'text', array('required' => true,'label' => 'Name',))
            ->add('sku', 'text', array('required' => true,'label' => 'SKU',))
            ->add('urlSlug', 'text', array('required' => true,'label' => 'URL Slug',))
            ->add('manufacturer', null, array('required' => false,'label' => 'Manufacturer',))
            ->add('manufacturerPart', 'text', array('required' => false,'label' => 'Manufacturer Part',))
            ->add('price', 'money', array('currency' => 'USD', 'required' => true,'label' => 'Price',))
            ->add('cost', 'money', array('currency' => 'USD', 'required' => false,'label' => 'Cost',))
            ->add('specialPrice', 'money', array('currency' => 'USD', 'required' => false,'label' => 'Special Price',))
            ->add('specialFromDate', 'date', array('widget' => 'single_text','format' => 'MM/dd/yyyy', 'required' => false,'label' => 'Special From',))
            ->add('specialToDate', 'date', array('widget' => 'single_text','format' => 'MM/dd/yyyy','required' => false,'label' => 'Special To',))
            ->add('minOrderQuantity', 'number', array('required' => false,'label' => 'Min Order Quantity',))
            ->add('maxOrderQuantity', 'number', array('required' => false,'label' => 'Max Order Quantity',))
            ->add('weight', 'text', array('required' => false,'label' => 'Weight',))
            ->add('weightUnits', 'choice', array('choices' => $this->getWeightUnits(), 'required' => false,'label' => 'Units',))
            ->add('dimensions', new DimensionsType(), array('required' => false,'label' => 'Dimensions',))
            ->add('dimensionsUnits', 'choice', array('choices' => $this->getDimensionUnits(), 'required' => false,'label' => 'Units',))
            ->add('manageStock', 'choice', array(
                'required' => false, 
                'label' => 'Manage Stock',
                'empty_value' => '',
                'choices' => array('1' => 'Yes','0' => 'No'),
                'multiple' => false,
                //'expanded' => true,
            ))
            ->add('isTaxable', 'choice', array(
                'required' => false,
                'label' => 'Taxable',
                'empty_value' => '',
                'choices' => array('1' => 'Yes','0' => 'No'),
                'multiple' => false,
                //'expanded' => true,
            ))
            ->add('isActive', 'choice', array(
                'required' => false,
                'label' => 'Active',
                'empty_value' => '',
                'choices' => array('1' => 'Yes','0' => 'No'),
                'multiple' => false,
                //'expanded' => true,
            ))
            ->add('availability', 'choice', array(
                'required' => true,
                'label' => 'Availability Status',
                'choices' => $this->getStatusChoices(),
                'multiple' => false,
            ))
            ->add('type', 'choice', array(
                'required' => true,
                'label' => 'Product Type',
                'choices' => $this->getTypeChoices(),
                'multiple' => false,
            ));


            if($this->getProduct()->getId()){
                $builder
                ->add('content', 'collection', array(
                    'type' => new ProductContentType($this->getConfigurationManager(), $this->getProduct()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                ))->add('images', 'collection', array(
                    'type' => new ProductImageType($this->getConfigurationManager(), $this->getProduct()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                ))                
                ->add('tierPrices', 'collection', array(
                    'type' => new ProductTierPriceType($this->getConfigurationManager(), $this->getProduct()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                ))
                ->add('relatedProducts', 'collection', array(
                	'type' => new ProductRelatedProductType($this->getConfigurationManager(), $this->getProduct()),
                	'allow_add' => true,
                	'by_reference' => false,
                	'allow_delete' => false,
                ))
                /*->add('bundledItems', 'collection', array(
                    'type' => new ProductBundledItemType($this->getConfigurationManager(), $this->getProduct()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                ))
                ->add('upsales', 'collection', array(
                    'type' => new ProductUpsaleType($this->getConfigurationManager(), $this->getProduct()),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => false,
                )) 
                ->add('attributes', new ProductAttributeCollectionType($this->getConfigurationManager(), $this->getProduct()), array(
                     'allow_add' => true,
                     'by_reference' => false,
                     'allow_delete' => false,
                ))


                ->add('specifications', new ProductSpecificationCollectionType($this->getConfigurationManager(), $this->getProduct()), array(
                     'allow_add' => true,
                     'by_reference' => false,
                     'allow_delete' => false,
                ))*/
                 ->add('routes', 'collection', array(
                     'type' => new ProductRouteType($this->getConfigurationManager()),
                     'allow_add' => true,
                     'by_reference' => false,
                     'allow_delete' => false,
                ));
                 ; 
            }
    }
    
    /**
     * getStatusChoices
     * 
     * @return array
     */
    protected function getStatusChoices()
    {
        $return = array();
         
        try{
            $classInfo = new \ReflectionClass('Spliced\Component\Commerce\Model\ProductInterface');
        } catch(\Exception $e) {
            return array('ERROR' => 'ERROR');
        }
         
        foreach($classInfo->getConstants() as $constant => $constantValue) {
            if(preg_match('/^AVAILABILITY\_/', $constant)) {
                $return[$constantValue] = ucwords(strtolower(str_replace(array('AVAILABILITY_','_'), array('',' '),$constant)));
            }
        }
        
        return $return;
    }
    

    /**
     * getTypeChoices
     *
     * @return array
     */
    protected function getTypeChoices()
    {
        $return = array();

        foreach($this->getTypeManager()->getHandlers() as $productTypeHandler) {
            $return[$productTypeHandler->getTypeCode()] = $productTypeHandler->getLabel();
        }
         
        return $return;
    }
    
    /**
     * getWeightUnits
     * 
     * @return array
     */
    protected function getWeightUnits()
    {
    	return array(
    		'G' => 'Grams',
    		'OZ' => 'Ounces',
    		'LB' => 'Pounds',
    	);
    }

    /**
     * getDimensionUnits
     * 
     * @return array
     */
    protected function getDimensionUnits()
    {
    	return array(
    		'IN' => 'Inches',
    		'YD' => 'Yards',
    		'FT' => 'Feet',
    	);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT),
        ));
    }
}