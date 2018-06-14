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
use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * FinalizeRegistrationFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FinalizeRegistrationFormType extends AbstractType
{

    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;    
    }
    
    protected function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        if($this->getCustomer()->getForceCollectEmail()){

            $builder->add('email', 'email', array('required' => true));
        }
        
        if($this->getCustomer()->getForcePasswordReset()){
            $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'SplicedCommerceBundle'),
                'first_options' => array('label' => 'Password', 'attr' => array('placeholder' => 'Password - At least 6 characters')),
                'second_options' => array('label' => 'Confirm Password', 'attr' => array('placeholder' => 'Repeat Password')),
                'invalid_message' => 'Passwords do not match',
            ));
        }
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\Customer',
            'validation_groups' => $this->getValidationGroups(),
        ));
        
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'finalize_registration';
    }
    
    /**
     * 
     */
    protected function getValidationGroups()
    {
        $return = array();
        if($this->getCustomer()->getForcePasswordReset()){
            $return[] = 'finalize_registration_password';
        }
        
        if($this->getCustomer()->getForceCollectEmail()){
            $return[] = 'finalize_registration_email';
        }
        
        return $return;
    }

}
