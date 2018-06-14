<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTimeEntryInterface;

/**
 * ProjectTimeEntryEvent
 */
class ProjectTimeEntryEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectTimeEntryInterface
     */
    protected $projectTimeEntry;

    /**
     * @param ProjectInterface $project
     * @param ProjectTimeEntryInterface $projectTimeEntry
     */
    public function __construct(ProjectInterface $project, ProjectTimeEntryInterface $projectTimeEntry)
    {
        $this->project = $project;
        $this->projectTimeEntry = $projectTimeEntry;
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
    public function getProjectTimeEntry()
    {
        return $this->projectTimeEntry;
    }


}
