<?php 

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BootstrapCheckboxFormType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array('choices' => array()));
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'bootstrap_checkbox';
	}
}