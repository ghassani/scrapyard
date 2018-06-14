<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectTagFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
  		$builder
  			->add('tag', 'entity', array(
			    'empty_value' => '',
			    'class' => 'SplicedProjectManagerBundle:Tag',
			    'required' => true,
			 ));
	}

	/**
	 *
	 */
	public function getName(){
		return 'project_tag';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectTag',
		));
	}
}