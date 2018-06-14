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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrderPayment
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_payment")
 * @ORM\Entity
 */
abstract class OrderPayment implements OrderPaymentInterface
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
     * @ORM\Column(name="payment_method", type="string", length=50)
     * @Assert\NotNull(message="Required")
     */
    protected $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="method_name", type="string", length=100)
     * @Assert\NotNull(message="Required")
     */
    protected $methodName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="payment_status", type="string")
     */
    protected $paymentStatus;
    
    /**
     * @ORM\OneToOne(targetEntity="Order", inversedBy="payment")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @ORM\OneToOne(targetEntity="CustomerCreditCard", mappedBy="payment", cascade={"persist"})
     */
    protected $creditCard;

    /**
     * @ORM\OneToMany(targetEntity="OrderPaymentMemo", mappedBy="payment", cascade={"persist"})
     */
    protected $memos;
    
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
        $this->memos = new ArrayCollection();
        $this->paymentStatus = OrderInterface::STATUS_INCOMPLETE;
    
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
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
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
    public function setCreditCard(CustomerCreditCardInterface $creditCard = null)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    /**
     * Get creditCard
     *
     * @return CustomerCreditCard
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * {@inheritDoc}
     */
    public function setMemos(Collection $memo = null)
    {
        $this->memos = $memos;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMemos()
    {
        return $this->memos;
    }

    /**
     * {@inheritDoc}
     */
    public function addMemo(OrderPaymentMemoInterface $memo)
    {
        $this->memos->add($memo->setPayment($this));

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
     * 
     */
     public function getCreditCardMethods()
     {
         return $this->creditCardMethods;
     }
    
    /**
     * setCreditCardMethods
     * 
     * @param array $methods
     */
     public function setCreditCardMethods($methods)
     {
         if(!is_array($methods)){
             $unserialized = unserialize($methods);
            if($unserialized !== false){
                $this->creditCardMethods = $unserialized;
                return $this;
            }
         }
         $this->creditCardMethods = $methods;
        return $this;
     }
     
    /**
     * addCreditCardMethod
     * 
     * @param string $method
     */
     public function addCreditCardMethod($method)
     {
         return $this->creditCardMethods[] = $method;
     }
     
}
