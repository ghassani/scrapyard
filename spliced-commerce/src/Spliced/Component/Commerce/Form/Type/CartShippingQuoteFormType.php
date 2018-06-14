<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CartShippingQuoteFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartShippingQuoteFormType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('country','country', array(
            'required' => false,
            'multiple' => false,
            'data' => 'US',
            'preferred_choices' => array('US','GB','CA'),
            'empty_value' => 'Select Destination Country',
        ))
        ->add('zipcode', 'hidden', array('required' => false));

    }


    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'cart_shipping_quote';
    }

}
