<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Spliced\Bundle\ProjectManagerBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Bundle\ProjectManagerBundle\Entity\ProjectMedia;

class ProjectMediaFormType extends AbstractType
{


    public function __construct(Project $project)
    {
        $this->project = $project;
    }

	public function buildForm(FormBuilderInterface $builder, array $options){
  		$builder
  			->add('displayType', 'choice', array(
                'empty_value' => '-Select One-',
                'choices' => array(
                    ProjectMedia::DISPLAY_TYPE_SPLASH => 'Splash',
                    ProjectMedia::DISPLAY_TYPE_SMALL => 'Small',
                    ProjectMedia::DISPLAY_TYPE_MEDIUM => 'Medium',
                    ProjectMedia::DISPLAY_TYPE_LARGE => 'Large',
			    ),
                'label' => 'project_media_form.display_type_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
			->add('file','file', array(
                'required' => true,
                'label' => 'project_media_form.file_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
			->add('fileCode', 'textarea', array(
                'required' => false,
                'label' => 'project_media_form.file_code_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
			->add('isPublic', 'bootstrap_radio', array(
                'choices' => array('No','Yes'),
                'required' => false,
                'label' => 'project_media_form.is_public_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
			;
	}

	
	/**
	 *
	 */
	public function getName(){
		return 'project_media';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectMedia',
		));
	}
}