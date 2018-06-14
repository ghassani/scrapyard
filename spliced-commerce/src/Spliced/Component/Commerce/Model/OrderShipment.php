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
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * OrderShipment
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_shipment")
 * @ORM\Entity()
 */
abstract class OrderShipment implements OrderShipmentInterface
{
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="shipment_provider", type="string", length=100)
     * @Assert\NotNull(message="Required")
     */
    protected $shipmentProvider;

    /**
     * @var string
     *
     * @ORM\Column(name="shipment_method", type="string", length=100)
     * @Assert\NotNull(message="Required")
     */
    protected $shipmentMethod;
    
    /**
     * @var string
     *
     * @ORM\Column(name="method_name", type="string", length=100)
     * @Assert\NotNull(message="Required")
     */
    protected $methodName;

    /**
     * @var float
     *
     * @ORM\Column(name="shipment_cost", type="decimal")
     */
    protected $shipmentCost;

    /**
     * @var float
     *
     * @ORM\Column(name="shipment_paid", type="decimal")
     */
    protected $shipmentPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="is_insured", type="boolean")
     */
    protected $isInsured;

    /**
     * @var string
     *
     * @ORM\Column(name="shipment_status", type="string")
     */
    protected $shipmentStatus;

    /**
     * @ORM\OneToOne(targetEntity="Order", inversedBy="shipment")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

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
     * @ORM\OneToMany(targetEntity="OrderShipmentMemo", mappedBy="shipment", cascade={"persist"})
     */
    protected $memos;
    
    /** @var string $userSelection */
    protected $userSelection; // used to handle any form submissions to match shipping method by full name ({provider}_{method})
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shipmentPaid = 0.0;
        $this->shipmentCost = 0.0;
        $this->shipmentStatus = OrderInterface::STATUS_INCOMPLETE;
        $this->memos = new ArrayCollection();
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
     * {@inheritDoc}
     */
    public function setShipmentProvider($shipmentProvider)
    {
        $this->shipmentProvider = $shipmentProvider;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShipmentProvider()
    {
        return $this->shipmentProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function setShipmentMethod($shipmentMethod)
    {
        $this->shipmentMethod = $shipmentMethod;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShipmentMethod()
    {
        return $this->shipmentMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setShipmentCost($shipmentCost)
    {
        $this->shipmentCost = $shipmentCost;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShipmentCost()
    {
        return $this->shipmentCost;
    }

    /**
     * {@inheritDoc}
     */
    public function setShipmentPaid($shipmentPaid)
    {
        $this->shipmentPaid = $shipmentPaid;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShipmentPaid()
    {
        return $this->shipmentPaid;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsInsured($isInsured)
    {
        $this->isInsured = $isInsured;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getIsInsured()
    {
        return $this->isInsured;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritDoc}
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShipmentStatus()
    {
        return $this->shipmentStatus;
    }

    /**
     * {@inheritDoc}
     */
    public function setShipmentStatus($shipmentStatus)
    {
        $this->shipmentStatus = $shipmentStatus;
        return $this;
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * getMethodName
     *
     * @return string
     */
    public function getMethodName()
    {
    	return $this->methodName;
    }
    
    /**
     * setMethodName
     *
     * @param string methodName
     *
     * @return self
     */
    public function setMethodName($methodName)
    {
    	$this->methodName = $methodName;
    	return $this;
    }
    
    /**
     * getMemos
     * 
     * @return Collection
     */
    public function getMemos()
    {
        return $this->memos;
    }
    
    /**
     * setMemos
     * 
     * @param array|ArrayCollection $memos
     */
    public function setMemos($memos)
    {
        if(is_array($memos)){
            $memos = new ArrayCollection($memos);
        } else if(!$memos instanceof Collection) {
            throw new \Exception('Memos must be array or instance of Colleciton');
        }
        $this->memos = $memos;
        return $this;
    }
    
    /**
     * addMemo
     * 
     * @param OrderShipmentMemoInterface $memo
     */
    public function addMemo(OrderShipmentMemoInterface $memo)
    {
        $this->memos->add($memo->setShipment($this));
        return $this;
    }
    
    /**
     * hasTrackingNumber
     * 
     * @param string $trackingNumber
     */
    public function hasTrackingNumber($trackingNumber)
    {
        foreach($this->memos as $memo){
            if($memo->getTrackingNumber() == $trackingNumber) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * getUserSelection
     * 
     * @return string
     */
     public function getUserSelection()
     {
         return $this->userSelection;
     }
     
     /**
      * setUserSelection
      * 
      * @param string $userSelection
      */
      public function setUserSelection($userSelection)
      {
          $this->userSelection = $userSelection;
        return $this;
      }
}
