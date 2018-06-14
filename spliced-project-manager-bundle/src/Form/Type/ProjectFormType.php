<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Spliced\Bundle\ProjectManagerBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectFormType extends AbstractType
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
  			->add('name')
			->add('urlSlug')
            ->add('client')
            /*->add('staff', 'collection', array(
                'type' => new ProjectStaffFormType($this->project),
                'allow_add'    => true,
                'allow_delete' => true,
            ))
            ->add('attributes', 'collection', array(
                'type' => new ProjectAttributeFormType($this->project),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => true,
            ))->add('media', 'collection', array(
                'type' => new ProjectMediaFormType($this->project),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => true,
            ))*/
        ;
	}
	
	/**
	 *
	 */
	public function getName(){
		return 'project';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\Project',
		));
	}
}