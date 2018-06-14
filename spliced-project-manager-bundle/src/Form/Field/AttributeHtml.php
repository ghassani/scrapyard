<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

class AttributeHtml{
	
	
	public function __construct(&$builder){
		$builder
  			->add('value', 'textarea', array('required' => false));
	}
	
}
