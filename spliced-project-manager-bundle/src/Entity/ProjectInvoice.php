<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInvoiceInterface;

/**
 * ProjectInvoice
 */
class ProjectInvoice implements ProjectInvoiceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $completionDate;
    
    /**
     * @var \DateTime
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var int
     */
    private $status;
    
    /**
     * @var \Project
     */
    private $project;
    
    /**
     * @var ArrayCollection
     */
    protected $lineItems;
    
    /**
     * 
     */
    public function __construct(){
    	$this->lineItems = new ArrayCollection();
    	$this->createdAt = new \DateTime();
    	$this->updatedAt = new \DateTime();
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return ProjectInvoice
     */
    public function setStartDate($startDate)
    {
        if(!$startDate instanceof \DateTime && ! is_null($startDate)){
    		$startDate = new \DateTime($startDate);
    	}
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set estimatedCompletionDate
     *
     * @param \DateTime $estimatedCompletionDate
     * @return ProjectInvoice
     */
    public function setCompletionDate($completionDate)
    {
    	if(!$completionDate instanceof \DateTime && ! is_null($completionDate)){
    		$completionDate = new \DateTime($completionDate);
    	}
        $this->completionDate = $completionDate;
    
        return $this;
    }

    /**
     * Get completionDate
     *
     * @return \DateTime 
     */
    public function getCompletionDate()
    {
        return $this->completionDate;
    }

    /**
     * @return \Project
     */
    public function getProject(){
    	return $this->project;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function setProject(Project $project){
    	$this->project = $project;
    	return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ProjectInvoice
     */
    public function setCreatedAt($createdAt)
    {
    	$this->createdAt = $createdAt;
    
    	return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ProjectInvoice
     */
    public function setUpdatedAt($updatedAt)
    {
    	$this->updatedAt = $updatedAt;
    
    	return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
    	return $this->updatedAt;
    }

    /**
     * @param Collection $lineItems
     * @return $this
     */
    public function setLineItems(Collection $lineItems){
    	$this->lineItems = $lineItems;
    	return $this;
    }

    /**
     * @param ProjectInvoiceLineItem $item
     * @return $this
     */
    public function addLineItem(ProjectInvoiceLineItem $item){
    	$item->setInvoice($this);
    	$this->lineItems->add($item);
    	return $this;
    }

    /**
     * @param ProjectInvoiceLineItem $item
     * @return $this
     */
    public function removeLineItem(ProjectInvoiceLineItem $item){
    	$this->lineItems->remove($item);
    	return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLineItems(){
    	return $this->lineItems;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

}
