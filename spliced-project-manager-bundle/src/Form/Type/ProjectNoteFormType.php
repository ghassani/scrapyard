<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectNoteFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('note', 'textarea', array(
            'required' => true,
            'label' => 'project_note_form.note_label',
            'translation_domain' => 'SplicedProjectManagerBundle',
        ))
		->add('clientViewable', 'checkbox', array(
            'value' => true,
            'required' => false,
            'label' => 'project_note_form.client_viewable_label',
            'translation_domain' => 'SplicedProjectManagerBundle',
        ));
	}
	
	/**
	 *
	 */
	public function getName(){
		return 'project_note';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectNote',
		));
	}
}