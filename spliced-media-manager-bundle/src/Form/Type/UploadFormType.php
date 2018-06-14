<?php 

namespace Spliced\Bundle\MediaManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class UploadFormType extends AbstractType
{
	
	/**
	 * buildForm
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)	{
		$builder
		->add('repository', 'hidden', array('required' => true))
		->add('subPath', 'hidden', array('required' => false));
	}
	
	/**
	 * getName
	 */
	public function getName(){
		return 'media_upload_file';
	}
	
	/**
	 * getDefaultOptions
	 */
	public function getDefaultOptions(array $options){
		return array(
			//'data_class' => 'Spliced\Bundle\MediaManagerBundle\Entity\Repository',
		);
	}
	
}