<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectStaffInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * ProjectStaff
 */
class ProjectStaff implements ProjectStaffInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $position;

	
    /**
     * @var \Project
     */
    private $project;

    /**
     * @var \Staff
     */
    private $staff;


	
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
     * Set project
     *
     * @param \Spliced\Bundle\WebsiteBundle\Entity\Project $project
     * @return ProjectTag
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
     * Set tag
     *
     * @param Staff $staff
     * @return ProjectStaff
     */
    public function setStaff(Staff $staff)
    {
        $this->staff = $staff;
    
        return $this;
    }

    /**
     * Get tag
     *
     * @return Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }
	

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }
	

    public function setPosition($position)
    {
        return $this->position = $position;
		return $this;
    }

    
}