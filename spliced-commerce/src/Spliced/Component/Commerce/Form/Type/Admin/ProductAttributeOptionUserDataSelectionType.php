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
 * ProductAttributeOptionUserDataSelectionType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionUserDataSelectionType extends AbstractType
{
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('required', 'choice', array(
            'required' => false, 
            'label' => 'Required',
            'expanded' => false,
            'multiple' => false,
            'choices' => array(
                1 => 'Yes',
                0 => 'No',
            )
        ))
        ->add('collection_type', 'choice', array(
            'label' => 'Data Collection Type', 
            'required' => true,
            'choices' => array(
                1 => 'Once for each item in cart',
                2 => 'Once for all items in cart',
            )
        ))
        ->add('required_error_message', 'text', array(
            'required' => false,
            'label' => 'Required Error Message',
        ))
        ->add('help', 'textarea', array(
            'required' => false,
            'label' => 'Help HTML',
        ))
        ->add('input_class', 'text', array(
            'required' => false,
            'label' => 'Input Class',
        ))
        ->add('label_class', 'text', array(
            'required' => false,
            'label' => 'Label Class',
        ))
        ->add('wrapper_class', 'text', array(
            'required' => false,
            'label' => 'Wrapper Class',
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option_user_data_selection';
    }
}