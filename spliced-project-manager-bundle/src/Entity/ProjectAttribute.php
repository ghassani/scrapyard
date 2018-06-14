<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectAttributeInterface;

/**
 * ProjectAttribute
 */
class ProjectAttribute implements ProjectAttributeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \Project
     */
    private $project;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->type = 'text';
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
     * Set value
     *
     * @param string $value
     * @return ProjectAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}