<?php

namespace Spliced\Bundle\ProjectManagerBundle\Twig\Extension;

use Spliced\Component\Twig\Extension\FilesystemExtension as BaseExtension;

class FilesystemExtension extends BaseExtension{
	
	public function __construct($appConfig, $kernel){
		parent::__construct($kernel);
		
		$this->appConfig = $appConfig;
		$this->webPath = $appConfig->get('web_path');
	}	
	
    public function assetExists($file){
		return file_exists($this->webPath.DIRECTORY_SEPARATOR.$file);
    }
}
