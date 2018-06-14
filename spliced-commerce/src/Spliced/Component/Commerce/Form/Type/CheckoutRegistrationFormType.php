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
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

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
        $builder->add('plainPassword', 'repeated', array(
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
        ));
        
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
            'constraints' => array(
                new Assert\Callback(function($customer, ExecutionContextInterface $context){
                    $plainPassword = $customer->getPlainPassword();
                    
                    if ($plainPassword && strlen($plainPassword) < 6) {
                        $context->addViolationAt('plainPassword', 'Must be at least 6 characters long.', array(), null);
                    }  
                })
            )
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
