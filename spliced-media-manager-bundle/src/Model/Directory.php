<?php

namespace Spliced\Bundle\MediaManagerBundle\Model;

use Symfony\Component\Finder\SplFileInfo;

class Directory extends SplFileInfo{
	
	protected $repository;
	
	public function  __construct(SplFileInfo $file, $repository){
		parent::__construct($file, $file->getRelativePath(), $file->getRelativePathname());
		$this->repository = $repository;
	}
	
	public function getSubPath(){
		return preg_replace('/^\//','',str_replace($this->repository->getPath(),'',$this->getRealPath()));
	}
	
	public function getSubPathBackOne(){
		$parts = explode(DIRECTORY_SEPARATOR,$this->getSubPath());
		unset($parts[count($parts)-1]);
		return implode(DIRECTORY_SEPARATOR,$parts);
	}
}
