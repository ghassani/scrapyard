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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderShipmentMemo
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_shipment_memo")
 * @ORM\Entity()
 */
abstract class OrderShipmentMemo implements OrderShipmentMemoInterface
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
     * @var bool
     *
     * @ORM\Column(name="customer_notified", type="boolean")
     */
    protected $customerNotified;
    
    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=50)
     */
    protected $createdBy;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tracking_number", type="text")
     */
    protected $trackingNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="memo", type="text")
     */
    protected $memo;
    
    /**
     * @var array
     *
     * @ORM\Column(name="memo_data", type="array")
     */
    protected $memoData;
    
    /**
     * @var string
     *
     * @ORM\Column(name="previous_status", type="string", length=50)
     */
    protected $previousStatus;
    
    /**
     * @var string
     *
     * @ORM\Column(name="changed_status", type="string", length=50)
     */
    protected $changedStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="OrderShipment", inversedBy="memos")
     * @ORM\JoinColumn(name="shipment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $shipment;
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;
    
    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
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
     *
     */
    public function setCustomerNotified($customerNotified){
        $this->customerNotified = $customerNotified;
    }
    
    /**
     *
     */
    public function getCustomerNotified()
    {
        return $this->customerNotified;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * {@inheritDoc}
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * {@inheritDoc}
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * {@inheritDoc}
     */
    public function setMemoData($memoData)
    {
        $this->memoData = $memoData;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMemoData()
    {
        return $this->memoData;
    }

    /**
     * {@inheritDoc}
     */
    public function setPreviousStatus($previousStatus)
    {
        $this->previousStatus = $previousStatus;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPreviousStatus()
    {
        return $this->previousStatus;
    }

    /**
     * {@inheritDoc}
     */
    public function setChangedStatus($changedStatus)
    {
        $this->changedStatus = $changedStatus;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChangedStatus()
    {
        return $this->changedStatus;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * {@inheritDoc}
     */
    public function setShipment(OrderShipmentInterface $shipment)
    {
        $this->shipment = $shipment;

        return $this;
    }
   
    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
