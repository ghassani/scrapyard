<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectAttributeInterface;

/**
 * ProjectAttributeEvent
 */
class ProjectAttributeEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectAttributeInterface
     */
    protected $projectAttribute;

    /**
     * @param ProjectInterface $project
     * @param ProjectAttributeInterface $projectAttribute
     */
    public function __construct(ProjectInterface $project, ProjectAttributeInterface $projectAttribute)
    {
        $this->project = $project;
        $this->projectAttribute = $projectAttribute;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return mixed
     */
    public function getProjectAttribute()
    {
        return $this->projectAttribute;
    }


}
