<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Spliced\Bundle\ProjectManagerBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectStaffFormType extends AbstractType
{

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
  		$builder
  			->add('staff', 'entity', array(
                'label' => 'project_staff_form.staff_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
                'class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\Staff'
            ))
			->add('position', 'text', array(
                'label' => 'project_staff_form.position_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ));
	}

    /**
	 *
	 */
	public function getName(){
		return 'project_staff';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectStaff',
			'cascade_validation' => true,
		));
	}
}