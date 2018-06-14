<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectInterface;

/**
 * Project
 */
class Project implements ProjectInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     */
    private $urlSlug;

    /**
     * @var $status
     */
    private $status;
	
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Client
     */
    private $client;
    
    /**
     * @var ArrayCollection
     */
    protected $attributes;

    /**
     * @var ArrayCollection
     */
    protected $media;

    /**
     * @var ArrayCollection
     */
    protected $timeEntries;

    /**
     * @var ArrayCollection
     */
    protected $staff;

    /**
     * @var ArrayCollection
     */
    protected $tags;

    /**
     * @var ArrayCollection
     */
    protected $files;

    /**
     * @var ArrayCollection
     */
    protected $invoices;

    /**
     * @var ArrayCollection
     */
    protected $notes;

    /**
     * @var ArrayCollection
     */
    protected $sites;
    
    /**
     * Constructor
     */
	public function __construct(){
		$this->attributes = new ArrayCollection();
		$this->media = new ArrayCollection();
		$this->messages = new ArrayCollection();
		$this->tags = new ArrayCollection();
		$this->staff = new ArrayCollection();
        $this->quotes = new ArrayCollection();
        $this->timeEntries = new ArrayCollection();
        $this->sites = new ArrayCollection();
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
	}

    /**
     * @return string
     */
    public function __toString(){
		return sprintf('%s - %s', $this->getId(), $this->getName());
	}

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'urlSlug' => $this->getUrlSlug(),
            'createdAt' => $this->getCreatedAt()->format('U'),
            'updatedAt' => $this->getUpdatedAt()->format('U'),
        );
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set urlSlug
     *
     * @param string $urlSlug
     * @return Project
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;
    
        return $this;
    }

    /**
     * Get urlSlug
     *
     * @return string 
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Project
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
     * @return Project
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
     * Set client
     *
     * @param Client $client
     * @return Project
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \Spliced\Bundle\WebsiteBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $attributes
     * @return $this
     */
    public function setAttributes($attributes){
        foreach ($attributes as $attribute) {
            $attribute->setProject($this);
        }
		$this->attributes = $attributes;
        return $this;
	}

    /**
     * @param ProjectAttribute $attribute
     * @return mixed
     */
    public function addAttribute(ProjectAttribute $attribute){
        return $this->attributes->add($attribute->setProject($this));
    }

    /**
     * @param ProjectAttribute $attribute
     * @return mixed
     */
    public function removeAttribute(ProjectAttribute $attribute){
        return $this->attributes->removeElement($attribute);
    }


    /**
     * @return ArrayCollection
     */
    public function getAttributes(){
        return $this->attributes;
    }

    /**
     * @param $name
     * @return bool
     */
	public function getAttribute($name){
        foreach($this->getAttributes() as $attribute) {
            if ($attribute->getName() == $name) {
                return $attribute;
            }
        }
        return false;
	}

    /**
     * @param $name
     * @return bool
     */
	public function hasAttribute($name){
        foreach($this->getAttributes() as $attribute) {
            if ($attribute->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $input
     * @return string
     */
	protected function decamelize($input) {
	  preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
	  $ret = $matches[0];
	  foreach ($ret as &$match) {
	    $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
	  }
	  return implode('_', $ret);
	}

    /**
     * @param $media
     */
	public function setMedia($media){
		$this->media = $media;	
	}

    /**
     * @param $media
     */
	public function addMedia($media){
		$this->media[] = $media;	
	}

    /**
     * @param null $displayType
     * @return ArrayCollection
     */
	public function getMedia($displayType = null){
		if(is_null($displayType)){
			return $this->media;
		}
		$displayType = strtolower($displayType);
		$return = new ArrayCollection();
		foreach($this->media as $media){
			if($media->getDisplayType() == $displayType){
				if(in_array($displayType,array(ProjectMedia::DISPLAY_TYPE_SPLASH))){
					return $media;
				} else {
					$return->add($media);
				}
			}
		}
		return $return;
	}

    /**
     * @param $tags
     * @return $this
     */
    public function setTags($tags){
		$this->tags = $tags;
		return $this;
	}

    /**
     * @return ArrayCollection|Collection
     */
    public function getTags(){
		if(!$this->tags instanceof Collection){
			$this->tags = new ArrayCollection($this->tags);
		}
		return $this->tags;
	}

    /**
     * @param $tag
     */
    public function addTag($tag){
		$this->tags[] = $tag;	
	}

    /**
     * @param $tag
     */
    public function removeTag($tag){
	
	}

    /**
     * @param $media
     */
    public function removeMedia($media){
	
	}

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status){
		$this->status = $status;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getStatus(){
		return $this->status;
	}

    /**
     * @return string
     */
    public function getStatusNamed(){
		switch($this->getStatus()){
			case 0:
			default:
				return 'In Process';
				break;
		}
	}

    /**
     * @return ArrayCollection
     */
    public function getStaff(){
		return $this->staff;
	}

    /**
     * @param $staff
     * @return $this
     */
    public function setStaff($staff){
		$this->staff = $staff;
		return $this;
	}

    /**
     * @param $staff
     * @return $this
     */
    public function addStaff($staff){
		$this->staff->add($staff);
		return $this;
	}

    /**
     * @return ArrayCollection
     */
    public function getFiles(){
		return $this->files;	
	}

    /**
     * @param $files
     * @return $this
     */
    public function setFiles($files){
		$this->files = $files;	
		return $this;
	}

    /**
     * @param $file
     * @return $this
     */
    public function addFile($file){
		$this->files[] = $file;	
		return $this;
	}

    /**
     * @return mixed
     */
	public function getNotes(){
		return $this->notes;
	}

    /**
     * @param $notes
     * @return $this
     */
	public function setNotes($notes){
		$this->notes = $notes;
		return $this;
	}

    /**
     * @param ProjectNote $note
     * @return $this
     */
	public function addNote(ProjectNote $note){
		$this->notes->add($note);
		return $this;
	}

    /**
     * @return mixed
     */
	public function getInvoices(){
		return $this->invoices;
	}

    /**
     * @param $invoice
     * @return $this
     */
	public function addInvoice($invoice){
		$this->invoices->add($invoices);
		return $this;
	}

    /**
     * @param null $invoices
     * @return $this
     */
	public function setInvoices($invoices = null){
		$this->invoices = $invoices;
		return $this;
	}

    /**
     * @return string
     */
	public function getInvoicesSum(){
		$sum = 0;
		foreach($this->invoices as $invoice){
			$sum += $invoice->getInvoicedAmount();
		}
		return number_format($sum,2,'.','');
	}

    /**
     * @param mixed $sites
     */
    public function setSites($sites)
    {
        $this->sites = $sites;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @return ArrayCollection
     */
    public function getTimeEntries()
    {
        return $this->timeEntries;
    }

    /**
     * @param ArrayCollection $timeEntries
     */
    public function setTimeEntries($timeEntries)
    {
        $this->timeEntries = $timeEntries;
        return $this;
    }



}