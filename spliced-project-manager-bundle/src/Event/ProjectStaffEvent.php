<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectStaffInterface;

/**
 * ProjectStaffEvent
 */
class ProjectStaffEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectStaffInterface
     */
    protected $projectStaff;

    /**
     * @param ProjectInterface $project
     * @param ProjectStaffInterface $projectStaff
     */
    public function __construct(ProjectInterface $project, ProjectStaffInterface $projectStaff)
    {
        $this->project = $project;
        $this->projectStaff = $projectStaff;
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
    public function getProjectStaff()
    {
        return $this->projectStaff;
    }


}
