<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ProductFilterType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterType extends AbstractType
{
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('id', 'number', array('required' => '','label' => 'ID',))
        ->add('name', 'text', array('required' => '','label' => 'Name',))
        ->add('nameOptions', 'choice', array(
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                        0 => 'Normal',
                        1 => 'Regular Expression',
                        2 => 'Does Not Contain',
                        3 => 'From Begining',
                        4 => 'From End',
                ),
                'label' => 'Name Filter Options',
                'data' => 0,
        ))
        ->add('sku', 'text', array('required' => '','label' => 'SKU',))
        ->add('skuOptions', 'choice', array(
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                        0 => 'Normal',
                        1 => 'Regular Expression',
                        2 => 'Does Not Contain',
                        3 => 'From Begining',
                        4 => 'From End',
                ),
                'label' => 'SKU Filter Options',
                'data' => 0,
        ))
        ->add('priceFrom', 'number', array('required' => '','label' => 'Price From',))
        ->add('priceTo', 'number', array('required' => '','label' => 'Price To',))
        ->add('costFrom', 'number', array('required' => '','label' => 'Cost From',))
        ->add('costTo', 'number', array('required' => '','label' => 'Cost To',))
        ->add('status', 'choice', array(
                'required' => false,
                'label' => 'Availability Status',
                'choices' => $this->getStatusChoices(),
                'multiple' => true,
        ))
        ->add('isActive', 'choice', array(
                'required' => false,
                'label' => 'Active',
                'empty_value' => '',
                'choices' => array('1' => 'Yes','0' => 'No'),
                'multiple' => false,
        ))
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CommerceAdminBundle\Model\ListFilter'
        )); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_filter';
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
}
