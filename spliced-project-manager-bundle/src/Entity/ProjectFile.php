<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectFileInterface;

/**
 * ProjectFile
 */
class ProjectFile implements ProjectFileInterface
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
    private $originalFilename;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $isPublic;


    /**
     * @var Project
     */
    private $project;

    /**
     * @var \SplFileInfo
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
     * @return ProjectFile
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
     * @return ProjectFile
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
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return ProjectFile
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
    
    
    /**
     * Get filename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
    	return $this->originalFilename;
    }
    
    /**
     * Set originalFilename
     *
     * @param string $originalFilename
     * @return ProjectFile
     */
    public function setOriginalFilename($originalFilename)
    {
    	$this->originalFilename = $originalFilename;
    
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

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    
}
