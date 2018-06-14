<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		
	}

	/**
	 *
	 */
	public function getName(){
		return 'tag';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\Tag',
		));
	}
}