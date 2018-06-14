<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Visitor
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="visitor")
 * @ORM\Entity()
 */
abstract class Visitor implements VisitorInterface
{

/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=64)
     */
    protected $sessionId;

    /**
     * @var $userAgent
     *
     * @ORM\Column(name="user_agent", type="string", length=255)
     */
    protected $userAgent;

    /**
     * @var $isBot
     *
     * @ORM\Column(name="is_bot", type="boolean")
     */
    protected $isBot;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20)
     */
    protected $ip;
    
    /**
     * @var string
     *
     * @ORM\Column(name="host_name", type="string", length=255)
     */
    protected $hostName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="initial_referer", type="string", length=50)
     */
    protected $initialReferer;

    /**
     * @var string
     *
     * @ORM\Column(name="initial_referer_url", type="string", length=255)
     */
    protected $initialRefererUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="VisitorRequest", mappedBy="visitor", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="visitor_id")
     */
    protected $requests;
    
    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="visitor")
     * @ORM\JoinColumn(name="id", referencedColumnName="visitor_id")
     */
    protected $orders;
    
    /**
     * Constructor
     *
     * @return Visitor
     */
    public function __construct()
    {
    	$this->requests = new ArrayCollection();
    	$this->orders = new ArrayCollection();
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
     * Set sessionId
     *
     * @param  string  $sessionId
     * @return Visitor
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Set ip
     *
     * @param  string         $ip
     * @return VisitorRequest
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }
    
    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Set hostName
     *
     * @param  string         $hostName
     * @return VisitorRequest
     */
    public function setHostName($hostName)
    {
        $this->hostName = $hostName;
    
        return $this;
    }
    
    /**
     * Get hostName
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->hostName;
    }
    
    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set initialReferer
     *
     * @param  string  $initialReferer
     * @return Visitor
     */
    public function setInitialReferer($initialReferer)
    {
        $this->initialReferer = $initialReferer;

        return $this;
    }

    /**
     * Get initialReferer
     *
     * @return string
     */
    public function getInitialReferer()
    {
        return $this->initialReferer;
    }

    /**
     * Set initialRefererUrl
     *
     * @param  string  $initialRefererUrl
     * @return Visitor
     */
    public function setInitialRefererUrl($initialRefererUrl)
    {
        $this->initialRefererUrl = $initialRefererUrl;

        return $this;
    }

    /**
     * Get initialRefererUrl
     *
     * @return string
     */
    public function getInitialRefererUrl()
    {
        return $this->initialRefererUrl;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Visitor
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
     * @param  \DateTime $updatedAt
     * @return Visitor
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
     * addRequest
     *
     * @param VisitorRequest $request
     *
     * @return Visitor
     */
    public function addRequest(VisitorRequestInterface $request)
    {
        if( ! $this->requests instanceof Collection) {
            $this->requests = new ArrayCollection(is_array($this->requests) ? $this->requests : array());
        }
        if(!$this->requests->contains($request)) {
            $request->setVisitor($this);
            $this->requests->add($request);
        }
        return $this;
    }

    /**
     * getRequests
     *
     * @return Collection
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * setRequests
     *
     * @param Collection $requests
     *
     * @return Visitor
     */
    public function setRequests(Collection $requests)
    {
        $this->requests = $requests;

        return $this;
    }
    
    /**
     * removeRequest
     *
     * @param VisitorRequest $request
     *
     * @return Visitor
     */
    public function removeRequest(VisitorRequestInterface $request)
    {
        $this->requests->removeElement($request);
        return $this;
    }

    /**
     * Set userAgent
     *
     * @param  string         $userAgent
     * @return VisitorRequest
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    
        return $this;
    }
    
    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
    
    /**
     * getIsBot
     * 
     * @return bool
     */
    public function getIsBot()
    {
        return $this->isBot;
    }

    /**
     * setIsBot
     * 
     * @param bool $isBot
     */
    public function setIsBot($isBot)
    {
        $this->isBot = $isBot;
        return $this;
    }

    /**
     * isBot
     * 
     * @return bool
     */
    public function isBot()
    {
        return $this->isBot;
    }
    
    /**
     * getOrders
     *
     * @return Collection
    */
    public function getOrders()
    {
    	return $this->orders;
    }

    /**
     * setOrders
     *
     * @param Collection orders
     *
     * @return self
    */
    public function setOrders($orders)
    {
	    $this->orders = $orders;
	    return $this;
    }
    
}
