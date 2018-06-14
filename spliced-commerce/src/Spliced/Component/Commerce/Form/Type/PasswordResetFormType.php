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
 * PasswordResetFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PasswordResetFormType extends AbstractType
{

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
          ->add('resetPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'SplicedCommerceBundle'),
            'first_options' => array('label' => 'New Password', 'attr' => array('placeholder' => 'Password - At least 6 characters')),
            'second_options' => array('label' => 'Confirm New Password', 'attr' => array('placeholder' => 'Repeat Password')),
            'invalid_message' => 'Passwords do not match',
          ))
          ;
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            //'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\CustomerProfile'
        ));
        
    }

    /**
     *
     */
    public function getName()
    {
        return 'password_reset';
    }

}
