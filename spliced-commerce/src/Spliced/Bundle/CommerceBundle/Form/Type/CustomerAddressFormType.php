<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CustomerAddressFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerAddressFormType extends AbstractType
{

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('addressLabel', 'text', array('required' => false))
          ->add('addressLabel', 'text', array('required' => true))
          ->add('firstName', 'text', array('required' => true))
          ->add('lastName', 'text', array('required' => true))
          ->add('attn', 'text', array('required' => false))
          ->add('address', 'text', array('required' => true))
          ->add('address2', 'text', array('required' => false))
          ->add('city', 'text', array('required' => true))
          ->add('state', 'text', array('required' => true))
          ->add('zipcode', 'text', array('required' => true))
          ->add('country', 'country', array(
                  'required' => true,
                  'empty_value' => 'Select a Country',
                  'preferred_choices' => array('US','CA','GB'), 
                    'data' => 'US',
          ))
          ->add('phoneNumber', 'text', array('required' => false));
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\CustomerAddress',
            'validation_groups' => array('add_address'),
        ));
    }

    /**
     *
     */
    public function getName()
    {
        return 'customer_address';
    }

}
