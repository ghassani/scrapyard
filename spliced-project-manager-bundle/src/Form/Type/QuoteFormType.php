<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuoteFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('contactName')
            ->add('companyName')
            ->add('phoneNumber')
            ->add('emailAddress')
            ->add('heardAboutUs')
            ->add('deadline');
	}

	/**
	 *
	 */
	public function getName(){
		return 'quote';
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