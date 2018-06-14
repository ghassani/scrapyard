<?php

namespace Spliced\Bundle\MediaManagerBundle\Model;

use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;

class MediaManager extends FileSystem{
	
	protected $repositories;
	protected $activeDirectory;
	protected $mediaCacher;
	protected $finfo;
	
	public function __construct(array $repositories, MediaCacher $mediaCacher){
		$this->repositories = new ArrayCollection();
		foreach($repositories as $repository){
			$this->repositories->add(new Repository($repository));
		}
		
		$this->mediaCacher = $mediaCacher;
		$this->finfo = finfo_open(FILEINFO_MIME_TYPE);
	}
	
	public function __destruct(){
		finfo_close($this->finfo);
	}
	
	/**
	 * 
	 */
	public function getDirectoryTree(Repository $repository, $subPath = null){
		$path = $repository->getPath();
		if($subPath){
			$path .= DIRECTORY_SEPARATOR.$subPath;
		}
		
		
		$finder = Finder::create()
		->in($path)
		->depth('== 0');
		
		$directories = array();
		$files = array();
		foreach($finder as $file){
			
			if($file->isDir()){
				$directories[$file->getFilename()] = new Directory($file, $repository);
				continue;
			}
			
			$files[$file->getFilename()] = new File($file, $repository, finfo_file($this->finfo, $path.DIRECTORY_SEPARATOR.$file->getFilename()));
		}
		
		ksort($files);
		ksort($directories);
		return new ArrayCollection(array_merge($directories,$files));
	}
	/**
	 * 
	 */
	public function getRepositories(){
		return $this->repositories;
	}
		 
	protected function inDirectories($path){
	 	foreach($this->repositories as $repository){
	 		if(strpos($repository->getPath(),$path) !== false){
	 			return true;
	 		}
	 	}
		return false;
	 }
	
	/**
	 * 
	 */
	public function findRepositoryByName($name){
		foreach($this->repositories as $repository){
			if(strtolower($repository->getName()) == strtolower($name)){
				return $repository;
			}
		}
		throw new RepositoryNotFoundException('Repository Does Not Exist');
	}
	

}
