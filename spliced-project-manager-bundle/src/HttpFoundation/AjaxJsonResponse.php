<?php

namespace Spliced\Bundle\ProjectManagerBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class AjaxJsonResponse extends Response{
	
	protected $returnData = array(
		'success' 	=> false,
	);
	
	public function __construct($content = '', $returnData = array()){
		$this->returnData = array_merge($this->returnData,$returnData);
		parent::__construct($content, 200, array('Content-Type'=>'application/json'));
	}
	
	/**
	 * @{inheritDoc}
	 */
	public function send(){
		$this->setContent(json_encode($this->returnData));
		parent::send();
	}
	
	/**
	 * 
	 */
	public function setData($key, $value, $encode = false){
		$this->returnData[$key] = $encode === true ? rawurlencode($value) : $value;
		return $this;
	}
	
	/**
	 * 
	 */
	public function getData($key){
		return isset($this->returnData[$key]) ? $this->returnData[$key] : null;
	}
}
