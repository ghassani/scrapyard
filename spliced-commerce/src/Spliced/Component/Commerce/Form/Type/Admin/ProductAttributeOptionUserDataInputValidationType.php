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

/**
 * ProductAttributeOptionUserDataInputValidationType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionUserDataInputValidationType extends AbstractType
{
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'choice', array(
            'label' => 'Input Validation', 
            'required' => true,
            'empty_value' => '-Select One-',
            'choices' => array(
                //1 => 'No Validation',
                2 => 'Alpha Only',
                3 => 'Numeric Only',
                4 => 'Alpha-Numeric',
                5 => 'E-Mail Address',
                6 => 'URL',
                7 => 'Luhn Checksum',
                8 => 'Regular Expression Match',
            )
        ))
        ->add('error_message', 'text', array(
            'required' => false,
            'label' => 'Validation Error Message',
        ))
        ->add('regular_expression', 'text', array(
            'required' => false,
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_attribute_option_user_data_input_validation';
    }
}