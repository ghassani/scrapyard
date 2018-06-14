<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Field;

class AttributeDatetime{
	
	
	public function __construct(&$builder){
		$builder
  			->add('value', 'text', array('required' => false));
	}
	
}
