<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuoteExtensionFormType extends AbstractType
{

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $services = explode(',', $this->settings['services']);

        $builder->add('contactName')
            ->add('companyName')
            ->add('phoneNumber')
            ->add('emailAddress')
            ->add('heardAboutUs', 'choice', array(
                'empty_value' => '-Select One-',
                'choices' => array(
                    'google' => 'Google',
                    'client' => 'Client',
                    'staff' => 'Staff Referral'
                )
            ))
            ->add('comments', 'textarea')
            ->add('services', 'choice', array(
                'expanded' => true,
                'multiple' => true,
                'choices' => array_combine($services, $services)
            ));
    }

    /**
     *
     */
    public function getName(){
        return $this->settings['formName'];
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\Quote',
        ));
    }
}