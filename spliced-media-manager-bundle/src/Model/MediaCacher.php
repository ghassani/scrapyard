<?php

namespace Spliced\Bundle\MediaManagerBundle\Model;

use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;
use Spliced\Bundle\MediaManagerBundle\Media\Adapter\ImageAdapter;

class MediaCacher extends FileSystem{
	
	protected $cacheDir;
	protected $imageAdapter;
	
	public function __construct($cacheDir, ImageAdapter $imageAdapter){
		$this->cacheDir = new \SplFileInfo($cacheDir);
		if(!$this->cacheDir->isDir()){
			$this->mkdir($this->getCacheDir(),0777);
		}
	}
	
	public function getCacheDir(){
		return $this->cacheDir->getPathInfo()->getRealPath().DIRECTORY_SEPARATOR.$this->cacheDir->getFilename();
	}
	
	/**
	 * 
	 */
	public function getThumbNail(\SplFileInfo $file){
		
	}
	
	/**
	 * 
	 */
	public function getImageAdapter(){
		return $this->imageAdapter;
	}
}

