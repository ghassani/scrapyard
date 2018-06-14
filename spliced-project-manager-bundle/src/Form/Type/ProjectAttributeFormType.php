<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Spliced\Bundle\ProjectManagerBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Bundle\ProjectManagerBundle\Entity\ProjectAttribute;
use Spliced\Bundle\ProjectManagerBundle\Form\Field;

class ProjectAttributeFormType extends AbstractType
{

	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name', 'text', array('required' => true))
            ->add('value', 'text', array('required' => true));
	}

	/**
	 *
	 */
	public function getName(){
		return 'project_attribute';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectAttribute',
		));
	}
}