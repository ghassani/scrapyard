<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectMediaInterface;

/**
 * ProjectMediaEvent
 */
class ProjectMediaEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectMediaInterface
     */
    protected $projectMedia;

    /**
     * @param ProjectInterface $project
     * @param ProjectMediaInterface $projectMedia
     */
    public function __construct(ProjectInterface $project, ProjectMediaInterface $projectMedia)
    {
        $this->project = $project;
        $this->projectMedia = $projectMedia;
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
    public function getProjectMedia()
    {
        return $this->projectMedia;
    }


}
