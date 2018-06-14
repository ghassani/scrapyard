<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;

use Spliced\Bundle\ProjectManagerBundle\Model\ProjectFileInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;

/**
 * ProjectFileEvent
 */
class ProjectFileEvent extends Event
{

    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectFileInterface
     */
    protected $projectFile;

    /**
     * @param ProjectInterface $project
     * @param ProjectFileInterface $projectFile
     */
    public function __construct(ProjectInterface $project, ProjectFileInterface $projectFile)
    {
        $this->project = $project;
        $this->projectFile = $projectFile;
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
    public function getProjectFile()
    {
        return $this->projectFile;
    }



}
