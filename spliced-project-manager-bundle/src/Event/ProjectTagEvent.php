<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTagInterface;

/**
 * ProjectTagEvent
 */
class ProjectTagEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectTagInterface
     */
    protected $projectTag;

    /**
     * @param ProjectInterface $project
     * @param ProjectTagInterface $projectTag
     */
    public function __construct(ProjectInterface $project, ProjectTagInterface $projectTag)
    {
        $this->project = $project;
        $this->projectTag = $projectTag;
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
    public function getProjectTag()
    {
        return $this->projectTag;
    }


}
