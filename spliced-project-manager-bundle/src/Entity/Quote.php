<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quote
 */
class Quote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $services;

    /**
     * @var string
     */
    private $contactName;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $heardAboutUs;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;



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
     * Set services
     *
     * @param string $services
     * @return Quote
     */
    public function setServices($services)
    {
        $this->services = $services;
    
        return $this;
    }

    /**
     * Get services
     *
     * @return string 
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Quote
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    
        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Quote
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    
        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Quote
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    
        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return Quote
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    
        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set heardAboutUs
     *
     * @param string $heardAboutUs
     * @return Quote
     */
    public function setHeardAboutUs($heardAboutUs)
    {
        $this->heardAboutUs = $heardAboutUs;
    
        return $this;
    }

    /**
     * Get heardAboutUs
     *
     * @return string 
     */
    public function getHeardAboutUs()
    {
        return $this->heardAboutUs;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Quote
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Quote
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
     * @return Quote
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
}