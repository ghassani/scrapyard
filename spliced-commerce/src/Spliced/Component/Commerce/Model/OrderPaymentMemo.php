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
 * OrderPayment
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_payment_memo")
 * @ORM\Entity()
 */
abstract class OrderPaymentMemo implements OrderPaymentMemoInterface
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
     * @ORM\Column(name="merchant_transaction_id", type="string", length=255)
     */
    protected $merchantTransactionId;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="amount_paid", type="decimal")
     */
    protected $amountPaid;
    
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
     * @ORM\ManyToOne(targetEntity="OrderPayment", inversedBy="memos")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $payment;

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
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * {@inheritDoc}
     */
    public function setPayment(OrderPaymentInterface $payment)
    {
        $this->payment = $payment;

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
     * setAmountPaid
     * 
     * @param decimal $amountPaid
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;
        return $this;
    }
    
    /**
     * getAmountPaid
     * 
     * @return decimal
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }
    
    /**
     * setMerchantTransactionId
     *
     * @param string $merchantTransactionId
     */
    public function setMerchantTransactionId($merchantTransactionId)
    {
        $this->merchantTransactionId = $merchantTransactionId;
        return $this;
    }
    
    /**
     * getMerchantTransactionId
     *
     * @return string
     */
    public function getMerchantTransactionId()
    {
        return $this->merchantTransactionId;
    }

    
}