<?php

namespace Spliced\Bundle\ProjectManagerBundle\Event;


use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInvoiceInterface;

/**
 * ProjectInvoiceEvent
 */
class ProjectInvoiceEvent extends Event
{
    /**
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var ProjectInvoiceInterface
     */
    protected $projectInvoice;

    /**
     * @param ProjectInterface $project
     * @param ProjectInvoiceInterface $projectInvoice
     */
    public function __construct(ProjectInterface $project, ProjectInvoiceInterface $projectInvoice)
    {
        $this->project = $project;
        $this->projectInvoice = $projectInvoice;
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
    public function getProjectInvoice()
    {
        return $this->projectInvoice;
    }


}
