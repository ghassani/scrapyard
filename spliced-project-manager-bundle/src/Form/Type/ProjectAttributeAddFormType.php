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

class ProjectAttributeAddFormType extends AbstractType
{
	
	protected $existingAttributes,
			  $availableAttributes,
			  $type;
	
	/**
	 * 
	 */
	public function __construct(Collection $existingAttributes, Collection $availableAttributes, $type = null){
		$this->existingAttributes = $existingAttributes;
		$this->availableAttributes = $availableAttributes;
		$this->type = $type;
	}
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$existingAttributes = $this->existingAttributes;
		$availableAttributes = $this->availableAttributes;
		

		/*$builder
  			->add('attribute', 'choice', array('choices' => $this->getAttributeChoices()));*/
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