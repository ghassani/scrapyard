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
 * OrderMemo
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_memo")
 * @ORM\Entity
 */
abstract class OrderMemo implements OrderMemoInterface
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
     * @ORM\Column(name="created_by", type="string", length=50)
     */
    protected $createdBy;
 
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
     * @ORM\Column(name="notification_type", type="string", length=100, nullable=true)
     */
    protected $notificationType;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="memos")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
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
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getNotificationType()
    {
        return $this->notificationType;
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
        if(!is_array($this->memoData)){
            $unserialized = unserialize($this->memoData);
            if($unserialized !== false){
                $this->memoData = $unserialized;
            }
        }
        return $this->memoData;
    }
    
}