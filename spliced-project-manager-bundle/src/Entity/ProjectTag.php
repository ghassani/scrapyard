<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTagInterface;

/**
 * ProjectTag
 */
class ProjectTag implements ProjectTagInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Project
     * })
     */
    private $project;

    /**
     * @var Tag
     */
    private $tag;



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
     * @param \Spliced\Bundle\WebsiteBundle\Entity\Tag $tag
     * @return ProjectTag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    
        return $this;
    }

    /**
     * Get tag
     *
     * @return \Spliced\Bundle\WebsiteBundle\Entity\Tag 
     */
    public function getTag()
    {
        return $this->tag;
    }
}