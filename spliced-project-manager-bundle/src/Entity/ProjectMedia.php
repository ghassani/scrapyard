<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectMediaInterface;

/**
 * ProjectMedia
 */
class ProjectMedia implements ProjectMediaInterface
{
	
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var string
     */
    private $fileCode;

    /**
     * @var boolean
     */
    private $displayType;

    /**
     * @var boolean
     */
    private $isPublic;

    /**
     * @var \Project
     */
    private $project;

    /**
     * @var SplFileInfo
     */
	protected $file;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return ProjectMedia
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return ProjectMedia
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    
        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set fileCode
     *
     * @param string $fileCode
     * @return ProjectMedia
     */
    public function setFileCode($fileCode)
    {
        $this->fileCode = $fileCode;
    
        return $this;
    }

    /**
     * Get fileCode
     *
     * @return string 
     */
    public function getFileCode()
    {
        return $this->fileCode;
    }

    /**
     * Set isMain
     *
     * @param boolean $isMain
     * @return ProjectMedia
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
    
        return $this;
    }

    /**
     * Get isMain
     *
     * @return boolean 
     */
    public function getIsMain()
    {
        return $this->isMain;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return ProjectMedia
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    
        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set project
     *
     * @param \Spliced\Bundle\WebsiteBundle\Entity\Project $project
     * @return ProjectMedia
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Spliced\Bundle\WebsiteBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
	
	public function getDisplayType(){
		return $this->displayType;
	}
	
	public function setDisplayType($displayType){
		$this->displayType = $displayType;
		return $this;
	}
	
	public function isImage(){
		return preg_match('/^image/i',$this->getFileType());
	}
	
	public function getFile(){
		return $this->file;
	}
	public function setFile($file){
		$this->file = $file;
		return $this;
	}
	public function getFileExtension(){
		return @end(explode('.',$this->getFilename()));
	}
}