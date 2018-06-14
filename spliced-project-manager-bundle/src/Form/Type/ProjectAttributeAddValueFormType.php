<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Bundle\ProjectManagerBundle\Entity\ProjectAttribute;
use Spliced\Bundle\ProjectManagerBundle\Form\Field;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ProjectAttributeAddValueFormType extends AbstractType
{
	
	protected $type;
	
	/**
	 * 
	 */
	public function __construct($type){
		$this->type = $type;
	}
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$existingAttributes = $this->existingAttributes;
		$availableAttributes = $this->availableAttributes;

		$builder
  			->add('value', 'choice', array(
                'label' => 'project_attribute_add_value_form.value_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
                'choices' => $this->getAttributeChoices()
            ));
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
			'cascade_validation' => true,
		));
	}
}