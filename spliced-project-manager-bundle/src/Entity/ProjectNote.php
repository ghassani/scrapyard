<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectNoteInterface;

/**
 * ProjectAttribute
 */
class ProjectNote implements ProjectNoteInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $note;

    /**
     * @var bool
     */
    private $clientViewable;
    
    /**
     * @var \Project
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;
    
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Project
     */
    private $project;


    /**
     * Constructor
     */
    public function __construct(){
    	$this->createdAt = new \DateTime();
    	$this->updatedAt = new \DateTime();	
    }
    
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ProjectAttribute
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ProjectAttribute
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set project
     *
     * @param Project $project
     * @return ProjectAttribute
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return
     */
    public function getCreatedBy(){
    	return $this->createdBy;
    }

    /**
     * @param $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy){
    	$this->createdBy = $createdBy;
    	return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isClientViewable()
    {
        return $this->clientViewable;
    }

    /**
     * @param boolean $clientViewable
     */
    public function setClientViewable($clientViewable)
    {
        $this->clientViewable = $clientViewable;
        return $this;
    }


}