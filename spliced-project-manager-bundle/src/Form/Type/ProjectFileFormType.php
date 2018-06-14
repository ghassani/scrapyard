<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Bundle\ProjectManagerBundle\Entity\ProjectMedia;

class ProjectFileFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
  		$builder
            ->add('file','file', array(
                'required' => true,
                'label' => 'project_file_form.file_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('description','textarea', array(
                'required' => false,
                'label' => 'project_file_form.description_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
  			->add('isPublic', 'checkbox', array(
                'required' => false,
                'value' => true,
                'label' => 'project_file_form.is_public_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ));
	}

	/**
	 *
	 */
	public function getName(){
		return 'project_file';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectFile',
		));
	}
}