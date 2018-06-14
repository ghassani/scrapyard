<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

class AttributeBoolean{
	
	
	public function __construct(&$builder){
		$builder
  			->add('value', 'boolean', array('required' => false));
	}
	
}
