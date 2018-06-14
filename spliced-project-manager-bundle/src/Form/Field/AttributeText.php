<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

class AttributeText{
	
	
	public function __construct(&$builder){
		$builder
  			->add('value', 'text', array('required' => false));
	}
	
}
