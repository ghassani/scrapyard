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

/**
 * OrderType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                            ->add('email')
            
                            ->add('orderNumber')
            
                            ->add('billingFirstName')
            
                            ->add('billingLastName')
            
                            ->add('billingAddress')
            
                            ->add('billingAddress2')
            
                            ->add('billingCity')
            
                            ->add('billingState')
            
                            ->add('billingZipcode')
            
                            ->add('billingCountry')
            
                            ->add('shippingName')
            
                            ->add('shippingAttn')
            
                            ->add('shippingAddress')
            
                            ->add('shippingAddress2')
            
                            ->add('shippingCity')
            
                            ->add('shippingState')
            
                            ->add('shippingZipcode')
            
                            ->add('shippingCountry')
            
                            ->add('startIp')
            
                            ->add('finishIp')
            
                            ->add('protectCode')
            
                            ->add('orderStatus')
            
                            ->add('completedAt')
            
                            ->add('createdAt')
            
                            ->add('updatedAt')
            /*
                            ->add('customer')
            
                            ->add('shipment')
            
                            ->add('payment')
            
                            ->add('visitor')*/
                    
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_order';
    }
}
