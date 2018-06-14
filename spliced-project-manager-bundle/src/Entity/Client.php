<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Bundle\ProjectManagerBundle\Model\ClientInterface;

/**
 * Client
 */
class Client implements ClientInterface
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
    private $companyName;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var
     */
    private $user;

    /**
     * @var ArrayCollection
     */
    protected $projects;

    /**
     * Constructor
     */
    public function __construct(){
    	$this->projects = new ArrayCollection();
    }
    
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
     * Set companyName
     *
     * @param string $companyName
     * @return Client
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
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection
     */
    public function getProjects(){
    	return $this->projects;
    }

    /**
     * @param $projects
     * @return $this
     */
    public function setProjects($projects){
    	$this->projects = $projects;
    	return $this;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function addProject(Project $project){
    	$this->projects->add($project);
    	return $this;
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