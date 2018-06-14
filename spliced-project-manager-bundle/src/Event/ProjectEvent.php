<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;

/**
 * ProjectEvent
 */
class ProjectEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;


    /**
     * @param ProjectInterface $project
     */
    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }


}
