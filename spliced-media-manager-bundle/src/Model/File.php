<?php

namespace Spliced\Bundle\MediaManagerBundle\Model;

use Symfony\Component\Finder\SplFileInfo;

class File extends SplFileInfo{
	
	protected $mimeType;
	protected $repository;
	
	public function  __construct($file, $repository, $mimeType = null){
		parent::__construct($file, $file->getRelativePath(), $file->getRelativePathname());
		$this->mimeType = $mimeType;
		$this->repository = $repository;
	}
	
	public function getMimeType(){
		return $this->mimeType;
	}
	
	public function isImage(){
		return preg_match('/^image/',$this->mimeType);
	}
	
	public function getSubPath(){
		$split = explode(DIRECTORY_SEPARATOR,preg_replace('/^\//','',str_replace($this->repository->getPath(),'',$this->getRealPath())));
		if(count($split) == 1){
			return '';
		}
		unset($split[count($split)-1]);
		return implode(DIRECTORY_SEPARATOR,$split).DIRECTORY_SEPARATOR;
		
	}
}
