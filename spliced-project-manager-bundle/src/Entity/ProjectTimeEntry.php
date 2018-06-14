<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectTimeEntryInterface;

/**
 * ProjectTimeEntry
 */
class ProjectTimeEntry implements ProjectTimeEntryInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Project
     */
    private $project;

    /**
     * @var DateTime
     */
    private $entryDate;

    /**
     * @var float
     */
    private $entryTime;

    /**
     * @var string
     */
    private $entryNote;

    /**
     * @var Staff
     */
    private $staff;

    /**
     * @var ProjectInvoice | null
     */
    private $invoice;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * @param DateTime $entryDate
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getEntryTime()
    {
        return $this->entryTime;
    }

    /**
     * @param float $entryTime
     */
    public function setEntryTime($entryTime)
    {
        $this->entryTime = $entryTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntryNote()
    {
        return $this->entryNote;
    }

    /**
     * @param string $entryNote
     */
    public function setEntryNote($entryNote)
    {
        $this->entryNote = $entryNote;
        return $this;
    }

    /**
     * @return Staff
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * @param Staff $staff
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;
        return $this;
    }

    /**
     * @return null|Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param null|Invoice $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
        return $this;
    }




}