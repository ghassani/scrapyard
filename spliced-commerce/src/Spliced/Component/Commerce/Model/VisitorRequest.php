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

/**
 * VisitorRequest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="visitor_request")
 * @ORM\Entity()
 */
abstract class VisitorRequest implements VisitorRequestInterface
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
     * @ORM\Column(name="requested_path", type="string", length=255)
     */
    protected $requestedPath;
    
    /**
     * @var string
     *
     * @ORM\Column(name="referer", type="string", length=255)
     */
    protected $referer;
    
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
     * @var Visitor
     *
     * @ORM\ManyToOne(targetEntity="Visitor", inversedBy="requests")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     * })
     */
    protected $visitor;

    /**
     * Constructor
     *
     * @return VisitorRequest
     */
    public function __construct()
    {
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
     * Set requestedPath
     *
     * @param  string         $requestedPath
     * @return VisitorRequest
     */
    public function setRequestedPath($requestedPath)
    {
        $this->requestedPath = $requestedPath;

        return $this;
    }

    /**
     * Get requestedPath
     *
     * @return string
     */
    public function getRequestedPath()
    {
        return $this->requestedPath;
    }

    /**
     * Set referer
     *
     * @param  string         $referer
     * @return VisitorRequest
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime      $createdAt
     * @return VisitorRequest
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
     * @param  \DateTime      $updatedAt
     * @return VisitorRequest
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

    /*
     * setVisitor
     *
     * @param Visitor $visitor
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * getVisitor
     *
     * @return Visitor
     */
    public function getVisitor()
    {
        return $this->visitor;
    }
}
