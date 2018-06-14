<?php

namespace Spliced\Bundle\ProjectManagerBundle\Helper;

use Symfony\Component\Yaml\Yaml;

class ListConfigurationLoader{
	
	public $data;
	
	public function __construct($configFilePath){
		if(!file_exists($configFilePath)){
			throw new \Exception($configFilePath.' Not Found');
		}
		$this->data = Yaml::parse($configFilePath);
	}
	
	public function getFields(){
		return isset( $this->data['fields']) ?  $this->data['fields'] : array();
	}
	
	public function getBatchActions(){
		return isset($this->data['batch_actions']) && count($this->data['batch_actions']) ? $this->data['batch_actions'] : array();
	}
	
	public function getActions(){
		return isset($this->data['actions']) && count($this->data['actions']) ? $this->data['actions'] : array();
	}
	
	public function get($key){
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
}
