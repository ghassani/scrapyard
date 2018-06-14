<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectNoteInterface;

/**
 * ProjectNoteEvent
 */
class ProjectNoteEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectNoteInterface
     */
    protected $projectNote;

    /**
     * @param ProjectInterface $project
     * @param ProjectNoteInterface $projectNote
     */
    public function __construct(ProjectInterface $project, ProjectNoteInterface $projectNote)
    {
        $this->project = $project;
        $this->projectNote = $projectNote;
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
    public function getProjectNote()
    {
        return $this->projectNote;
    }


}
