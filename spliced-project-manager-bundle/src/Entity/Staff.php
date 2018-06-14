<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\ProjectManagerBundle\Model\StaffInterface;

/**
 * Staff
 */
class Staff implements StaffInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $contactName;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var \User
     */
    private $user;

    /**
     * @return string
     */
	public function __toString(){
		return $this->getDisplayName();
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
     * Set contactName
     *
     * @param string $contactName
     * @return Client
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
     * Set displayName
     *
     * @param string $displayName
     * @return Client
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
    	if(strlen($this->displayName)){
    		return $this->displayName;
    	} else if(strlen($this->companyName)){
    		return $this->companyName;
    	}
        return $this->contactName;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Client
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Spliced\Bundle\WebsiteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}