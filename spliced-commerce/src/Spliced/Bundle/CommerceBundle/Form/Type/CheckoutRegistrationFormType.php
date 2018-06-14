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
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

/**
 * CheckoutRegistrationFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutRegistrationFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', 'email', array('required' => true))
        ->add('plainPassword', 'repeated', array(
            'required' => false,
            'type' => 'password',
            'first_options' => array(
                'required' => false, 
                'label' => 'Password',
                'attr' => array('placeholder' => 'At least 6 characters')
            ),
            'second_options' => array(
                'required' => false, 
                'label' => 'Confirm Password', 
                'attr' => array('placeholder' => 'Repeat Password')
            ),
            'invalid_message' => 'Passwords do not match',
        ))
        ->add('saveAddress', 'checkbox', array('required' => false, 'value' => true, 'data' => true, 'label' => 'Save Address(s) to Address Book'));
        
        /*
        $builder->addValidator(new CallbackValidator(function(FormInterface $form){
            $password = $form->get('plainPassword')->getViewData();
            
            if (!empty($password['first']) && empty($password['second'])) {
                $form['plainPassword']->addError(new FormError("Please repeat your password"));
            } else if (!empty($password['first']) && !empty($password['second'])) {
                
                if($password['first'] != $password['second']){
                    $form['plainPassword']->addError(new FormError("Your Passwords Must Match"));
                } else if(strlen($password['first']) < 6){
                    $form['plainPassword']->addError(new FormError("Password Must Be At Least 6 Characters"));
                }
            }
        }));*/
    } 

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\Customer',
            'validation_groups' => array('checkout_registration'),
        ));
    } 

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'checkout_registration';
    }

}
