<?php

namespace Spliced\Bundle\MediaManagerBundle\Twig\Extension;

use Spliced\Bundle\SaveHealthcareBundle\Configuration\Container;
use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Bundle\MediaManagerBundle\Model\MediaManager;

class MediaExtension extends \Twig_Extension
{
	
	public function __construct(MediaManager $mediaManager){
		$this->mediaManager = $mediaManager;	
	}
	
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
    	return array(
    		new \Twig_SimpleFunction('isArray', array($this, 'isArray')),
    		new \Twig_SimpleFunction('mediaThumbnail', array($this, 'mediaThumbnail')),
    	);
    } 

    /**
	* Returns a list of filters.
	*
	* @return array
	*/
    public function getFilters()
    {
        $filters = array(
            'convertFromBytes' => new \Twig_Filter_Method($this, 'convertFromBytes'),
            //'convertFromBytes' => new \Twig_Filter_Function('twig_convert_from_bytes', array()),
        );
        return $filters;
    }
	
    public function isArray($value){
		return is_array($value) || $value instanceof ArrayCollection;
    }
	
	function convertFromBytes($value){		
		$value = (int) $value;
		if(1048576 <= $value){
			return number_format(($value / 1048576),2).'MB';
		}
		
		return number_format(($value / 1024),2).'KB';
	}
	
    public function mediaThumbnail($value){
		$this->adapter->open($filePath);
		$this->adapter->keepAspectRatio(true);
		$this->adapter->keepFrame(true);
		$this->adapter->keepTransparency(true);
		$this->adapter->constrainOnly(true);
		#$this->adapter->backgroundColor($this->_backgroundColor);
		$this->adapter->quality(100);
		$this->adapter->resize($toWidth, $toHeight);
		$this->adapter->save($cacheDir,$cacheName); 
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_media_manager_media';
    }

}

