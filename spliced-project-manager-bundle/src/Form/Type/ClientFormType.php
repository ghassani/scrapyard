<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientFormType extends AbstractType
{
	
	protected $isNew;
	
	public function __construct($isNew = false){
		$this->isNew = $isNew;
	}
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('contactName')
		->add('companyName')
		->add('displayName')
		->add('user', 'entity', array(
		    'class' => 'SplicedProjectManagerBundle:User',
			'empty_value' => '',
			'property' => 'listName',
		    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
		        return $er->createQueryBuilder('u')
		            ->orderBy('u.username', 'ASC');
		    },
		));
	}

	/**
	 *
	 */
	public function getName(){
		return 'client';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\Client',
		));
	}
}