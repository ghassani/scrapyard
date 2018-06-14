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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * RegistrationFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('email', 'email', array('required' => true)) 
        ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'SplicedCommerceBundle'),
            'first_options' => array('label' => 'Password', 'attr' => array('placeholder' => 'Password - At least 6 characters')),
            'second_options' => array('label' => 'Confirm Password', 'attr' => array('placeholder' => 'Repeat Password')),
            'invalid_message' => 'Passwords do not match',
        ))
        ->add('firstName', 'text', array())
        ->add('lastName', 'text', array())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\Customer',
            'validation_groups' => array('new_registration'),
        ));
    }

    public function getName()
    {
        return 'commerce_user_registration';
    }
}
