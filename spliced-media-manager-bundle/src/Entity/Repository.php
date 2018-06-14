<?php

namespace Spliced\Bundle\MediaManagerBundle\Entity;

class Repository{
	
	protected $type;
	protected $name;
	protected $path;
	protected $webPath;
	
	public function getType(){
		return $this->type;
	}
	
	public function setType($type){
		$this->type = $type;
		return $this;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
		return $this;
	}

	public function getPath(){
		return $this->path;
	}
	
	public function setPath($path){
		$this->path = $path;
		return $this;
	}

	public function getWebPath(){
		return $this->path;
	}
	
	public function setWebPath($path){
		$this->webPath = $path;
		return $this;
	}
}