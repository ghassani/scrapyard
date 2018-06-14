<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

class AttributeUrl{
	
	
	public function __construct(&$builder){
		$builder
  			->add('value', 'url', array('required' => false));
	}
	
}
