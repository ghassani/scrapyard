<?php 

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BootstrapRadioFormType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array(
				'1' => 'Yes',
				'0' => 'No',
			)
		));
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'bootstrap_radio';
	}
}