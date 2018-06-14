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
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\ExecutionContextInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Order
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order")
 * @ORM\Entity()
 */
abstract class Order implements OrderInterface
{
     /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=75, nullable=true)
     */
    protected $email;

    /**
     * @var string $billingPhoneNumber
     *
     * @ORM\Column(name="billing_phone_number", type="string", length=20, nullable=true)
     */
    protected $billingPhoneNumber;
    
    /**
     * @var string $shippingPhoneNumber
     *
     * @ORM\Column(name="shipping_phone_number", type="string", length=20, nullable=true)
     */
    protected $shippingPhoneNumber;
    
    /**
     * @var string $orderNumber
     *
     * @ORM\Column(name="order_number", type="string", length=50, nullable=true)
     */
    protected $orderNumber;

      /**
     * @var string $billingFirstName
     *
     * @ORM\Column(name="billing_first_name", type="string", length=50, nullable=true)
     */
    protected $billingFirstName;

    /**
     * @var string $billingLastName
     *
     * @ORM\Column(name="billing_last_name", type="string", length=50, nullable=true)
     */
    protected $billingLastName;

    /**
     * @var string $billingLastName
     *
     * @ORM\Column(name="billing_company", type="string", length=50, nullable=true)
     */
    protected $billingCompany;
    
    /**
     * @var string $billingAddress
     *
     * @ORM\Column(name="billing_address", type="string", length=150, nullable=false)
     */
    protected $billingAddress;

    /**
     * @var string $billingAddress2
     *
     * @ORM\Column(name="billing_address2", type="string", length=150, nullable=true)
     *
     */
    protected $billingAddress2;

    /**
     * @var string $billingCity
     *
     * @ORM\Column(name="billing_city", type="string", length=75, nullable=true)
     */
    protected $billingCity;

    /**
     * @var string $billingState
     *
     * @ORM\Column(name="billing_state", type="string", length=50, nullable=true)
     */
    protected $billingState;

    /**
     * @var string $billingZipcode
     *
     * @ORM\Column(name="billing_zipcode", type="string", length=15, nullable=true)
     */
    protected $billingZipcode;

    /**
     * @var string $billingCountry
     *
     * @ORM\Column(name="billing_country", type="string", length=4, nullable=true)
     */
    protected $billingCountry;

    /**
     * @var string $shippingName
     *
     * @ORM\Column(name="shipping_first_name", type="string", length=50, nullable=true)
     */
    protected $shippingFirstName;

    /**
     * @var string $shippingAttn
     *
     * @ORM\Column(name="shipping_last_name", type="string", length=50, nullable=true)
     */
    protected $shippingLastName;
    
    /**
     * @var string $billingLastName
     *
     * @ORM\Column(name="shipping_company", type="string", length=50, nullable=true)
     */
    protected $shippingCompany;

    /**
     * @var string $shippingAddress
     *
     * @ORM\Column(name="shipping_address", type="string", length=150, nullable=true)
     */
    protected $shippingAddress;

    /**
     * @var string $shippingAddress2
     *
     * @ORM\Column(name="shipping_address2", type="string", length=150, nullable=true)
     */
    protected $shippingAddress2;

    /**
     * @var string $shippingCity
     *
     * @ORM\Column(name="shipping_city", type="string", length=75, nullable=true)
     */
    protected $shippingCity;

    /**
     * @var string $shippingState
     *
     * @ORM\Column(name="shipping_state", type="string", length=50, nullable=true)
     */
    protected $shippingState;

    /**
     * @var string $shippingZipcode
     *
     * @ORM\Column(name="shipping_zipcode", type="string", length=15, nullable=true)
     */
    protected $shippingZipcode;

    /**
     * @var string $shippingCountry
     *
     * @ORM\Column(name="shipping_country", type="string", length=4, nullable=true)
     */
    protected $shippingCountry;

    /**
     * @var string $startIp
     *
     * @ORM\Column(name="start_ip", type="string", length=39, nullable=false)
     */
    protected $startIp;

    /**
     * @var string $finishIp
     *
     * @ORM\Column(name="finish_ip", type="string", length=39, nullable=true)
     */
    protected $finishIp;

    /**
     * @var string $protectCode
     *
     * @ORM\Column(name="protect_code", type="string", length=10, nullable=true)
     */
    protected $protectCode;
    
    /**
     * @var boolean $saveBillingAddress
     *
     * @ORM\Column(name="save_billing_address", type="boolean", nullable=true)
     */
    protected $saveBillingAddress;
    
    /**
     * @var boolean $saveShippingAddress
     *
     * @ORM\Column(name="save_shipping_address", type="boolean", nullable=true)
     */
    protected $saveShippingAddress;
    
    /**
     * @var boolean $orderStatus
     *
     * @ORM\Column(name="order_status", type="string", nullable=false)
     */
    protected $orderStatus;

    /**
     * @var string $completedAt
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    protected $completedAt;

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
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    protected $customer;

    /**
     * @ORM\OneToOne(targetEntity="OrderShipment", mappedBy="order", cascade={"persist"})
     */
    protected $shipment;

    /**
     * @ORM\OneToOne(targetEntity="OrderPayment", mappedBy="order", cascade={"persist"})
     */
    protected $payment;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="OrderCustomFieldValue", mappedBy="order", cascade={"persist"})
     */
    protected $customFields;
    
    /**
     * @ORM\OneToMany(targetEntity="OrderMemo", mappedBy="order", cascade={"persist"})
     */
    protected $memos;
    
    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Visitor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     * })
     */
    protected $visitor;

    /**
     * 
     */
    protected $customFieldValues;
    
    /**
     * Constructor
     *
     */
    public function __construct($status = null)
    {
        $this->items = new ArrayCollection();
        $this->memos = new ArrayCollection();
        $this->customFields = new ArrayCollection();
        $this->setOrderStatus(!is_null($status) ? $status : static::STATUS_INCOMPLETE);
        $this->generateProtectCode();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * __toString
     *
     */
    public function __toString()
    {
        return $this->getOrderNumber() ? $this->getOrderNumber() : $this->getId();
    }

    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * {@inheritDoc}
     */
    public function generateProtectCode()
    {
        $hash = md5(rand(100,1000));
        $this->setProtectCode(substr($hash,strlen($hash)-10));
        return $this;
    }

    /**
     * getProtectCode
     */
    public function getProtectCode()
    {
        return $this->protectCode;
    }

    /**
     * setProtectCode
     *
     * @param string $protectCode
     */
    public function setProtectCode($protectCode)
    {
        $this->protectCode = $protectCode;

        return $this;
    }

    /**
     * getTotalOrderedItems
     *
     * @return int
     */
    public function getTotalOrderedItems()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += (int) $item->getQuantity();
        }

        return $total;
    }

    /**
     * Set billingFirstName
     *
     * @param string $billingFirstName
     */
    public function setBillingFirstName($billingFirstName)
    {
        $this->billingFirstName = $billingFirstName;
        return $this;
    }

    /**
     * Get billingName
     *
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    /**
     * Get billingLastName
     *
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    /**
     * Set billingLastName
     *
     * @param string $billingLastName
     */
    public function setBillingLastName($billingLastName)
    {
        $this->billingLastName = $billingLastName;
        return $this;
    }
    
    /**
     * getBillingCompany
     *
     * @return string
    */
    public function getBillingCompany()
    {
    	return $this->billingCompany;
    }

    /**
     * setBillingCompany
     *
     * @param string billingCompany
     *
     * @return self
    */
    public function setBillingCompany($billingCompany)
    {
	    $this->billingCompany = $billingCompany;
	    return $this;
    }
    

    /**
     * Set billingAddress
     *
     * @param string $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set billingAddress2
     *
     * @param string $billingAddress2
     */
    public function setBillingAddress2($billingAddress2)
    {
        $this->billingAddress2 = $billingAddress2;
        return $this;
    }

    /**
     * Get billingAddress2
     *
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->billingAddress2;
    }

    /**
     * Set billingCity
     *
     * @param string $billingCity
     */
    public function setBillingCity($billingCity)
    {
        $this->billingCity = $billingCity;
        return $this;
    }

    /**
     * Get billingCity
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * Set billingState
     *
     * @param string $billingState
     */
    public function setBillingState($billingState)
    {
        $this->billingState = $billingState;
        return $this;
    }

    /**
     * Get billingState
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->billingState;
    }

    /**
     * Set billingZipcode
     *
     * @param string $billingZipcode
     */
    public function setBillingZipcode($billingZipcode)
    {
        $this->billingZipcode = $billingZipcode;
        return $this;
    }

    /**
     * Get billingZipcode
     *
     * @return string
     */
    public function getBillingZipcode()
    {
        return $this->billingZipcode;
    }

    /**
     * Set billingCountry
     *
     * @param string $billingCountry
     */
    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = $billingCountry;
        return $this;
    }

    /**
     * Get billingCountry
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    /**
     * setShippingFirstName
     *
     * @param string $shippingName
     */
    public function setShippingFirstName($shippingFirstName)
    {
        $this->shippingFirstName = $shippingFirstName;
        return $this;
    }

    /**
     * getShippingFirstName
     *
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shippingFirstName;
    }

    /**
     * setShippingLastName
     *
     * @param string $shippingAttn
     */
    public function setShippingLastName($shippingLastName)
    {
        $this->shippingLastName = $shippingLastName;
        return $this;
    }

    /**
     * getShippingLastName
     *
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shippingLastName;
    }
    
    /**
     * getShippingCompany
     *
     * @return string
    */
    public function getShippingCompany()
    {
    	return $this->shippingCompany;
    }

    /**
     * setShippingCompany
     *
     * @param string shippingCompany
     *
     * @return self
    */
    public function setShippingCompany($shippingCompany)
    {
	    $this->shippingCompany = $shippingCompany;
	    return $this;
    }
    

    /**
     * Set shippingAddress
     *
     * @param string $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set shippingAddress2
     *
     * @param string $shippingAddress2
     */
    public function setShippingAddress2($shippingAddress2)
    {
        $this->shippingAddress2 = $shippingAddress2;
        return $this;
    }

    /**
     * Get shippingAddress2
     *
     * @return string
     */
    public function getShippingAddress2()
    {
        return $this->shippingAddress2;
    }

    /**
     * Set shippingCity
     *
     * @param string $shippingCity
     */
    public function setShippingCity($shippingCity)
    {
        $this->shippingCity = $shippingCity;
        return $this;
    }

    /**
     * Get shippingCity
     *
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shippingCity;
    }

    /**
     * Set shippingState
     *
     * @param string $shippingState
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;
        return $this;
    }

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * Set shippingZipcode
     *
     * @param string $shippingZipcode
     */
    public function setShippingZipcode($shippingZipcode)
    {
        $this->shippingZipcode = $shippingZipcode;
        return $this;
    }

    /**
     * Get shippingZipcode
     *
     * @return string
     */
    public function getShippingZipcode()
    {
        return $this->shippingZipcode;
    }

    /**
     * Set shippingCountry
     *
     * @param string $shippingCountry
     */
    public function setShippingCountry($shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;
        return $this;
    }

    /**
     * Get shippingCountry
     *
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }

    /**
     * Set orderStatus
     *
     * @param boolean $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return boolean
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $value)
    {
        $this->createdAt = $value;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $value)
    {
        $this->updatedAt = $value;

        return $this;
    }

    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    public function setCompletedAt(\DateTime $value = null)
    {
        $this->completedAt = $value;

        return $this;
    }
    /**
     * Set customer
     *
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * setStartIp
     * @param string $finishIp
     */
    public function setStartIp($startIp)
    {
        $this->startIp = $startIp;

        return $this;
    }

    /**
     * getStartIp
     */
    public function getStartIp()
    {
        return $this->startIp;
    }

    /**
     * setFinishIp
     * @param string $finishIp
     */
    public function setFinishIp($finishIp)
    {
        $this->finishIp = $finishIp;

        return $this;
    }

    /**
     * getFinishIp
     */
    public function getFinishIp()
    {
        return $this->finishIp;
    }

    /**
     * getBillingPhoneNumber
     */
    public function getBillingPhoneNumber()
    {
        return $this->billingPhoneNumber;
    }
    
    /**
     * setBillingPhoneNumber
     *
     * @param string $billingPhoneNumber
     */
    public function setBillingPhoneNumber($billingPhoneNumber = null)
    {
        $this->billingPhoneNumber = $billingPhoneNumber;
        return $this;
    }

    /**
     * getShippingPhoneNumber
     */
    public function getShippingPhoneNumber()
    {
        return $this->shippingPhoneNumber;
    }
    
    /**
     * setShippingPhoneNumber
     *
     * @param string $shippingPhoneNumber
     */
    public function setShippingPhoneNumber($shippingPhoneNumber = null)
    {
        $this->shippingPhoneNumber = $shippingPhoneNumber;
        return $this;
    }
    /**
     * setEmail
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getEmail
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * getOrderNumber
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * setOrderNumber
     *
     * @param string $orderNumber
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * getShipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * setShipment
     * @param OrderShipmentInterface $shipment
     */
    public function setShipment(OrderShipmentInterface $shipment)
    {
        $shipment->setOrder($this);
        
        $this->shipment = $shipment;

        return $this;
    }

    /**
     * getPayment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * setPayment
     *
     * @param OrderPaymentInterface $payment
     */
    public function setPayment(OrderPaymentInterface $payment)
    {
        $payment->setOrder($this);
        
        $this->payment = $payment;

        return $this;
    }

    /**
     * hasAlternateShippingAddress
     *
     * @return bool
     */
    public function hasAlternateShippingAddress()
    {
        return !empty($this->shippingAddress) && $this->shippingAddress != $this->billingAddress;
    }

    /**
     * getBillingFullName
     *
     * @return string
     */
    public function getBillingFullName()
    {
        return trim(sprintf('%s %s',$this->getBillingFirstName(),$this->getBillingLastName()));
    }

    /**
     * getItems
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * setItems
     *
     * @param Collection $items
     */
    public function setItems(Collection $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * addItem
     *
     * @param
     */
    public function addItem(OrderItemInterface $item)
    {
        $this->items->add($item->setOrder($this));

        return $this;
    }

    /**
     * hasItem
     *
     * @param  OrderItemInterface $item
     * @return bool
     */
    public function hasItem($itemOrProduct)
    {
        foreach ($this->items as $item) {
            if ($item->getSku() == $itemOrProduct->getSku()) {
                return true;
            }
        }

        return false;
    }

    /**
     * getItem
     *
     * @param  OrderItemInterface $item
     * @return bool
     */
    public function getItem($itemOrProduct)
    {
        foreach ($this->items as $item) {
            if ($item->getSku() == $itemOrProduct->getSku()) {
                return $item;
            }
        }

        return null;
    }
    
    /**
     * getItemByCartItem
     *
     * @param  OrderItemInterface $item
     * @return bool
     */
    public function getItemByCartItem(CartItemInterface $cartItem)
    {
        $searchItemsFunction = function($items) use($cartItem, &$searchItemsFunction){
            foreach($items as $item){
                if($item->getCartItem() && $item->getCartItem()->getId() == $cartItem->getId()){
                    return $item;
                }
                if($item->hasChildren()){
                    $childMatch = $searchItemsFunction($item->getChildren());
                    if($childMatch){
                        return $childMatch;
                    }
                }
            }
            return null;
        };
            
        return $searchItemsFunction($this->getItems());
    } 
    
    /**
     * removeItem
     * 
     * @param CartItemInterface $orderItem
     */
    public function removeItem(OrderItemInterface $orderItem)
    {
        
        $searchItems = function($items) use($orderItem, &$searchItems){
            foreach ($items as $key => $item) {
                if($item->getId() == $orderItem->getId()){
                    $items->remove($key);
                    return true;
                }
                if($item->hasChildren()){
                    $childMatch = $searchItems($item->getChildren());
                    if($childMatch){
                        return true;
                    }
                }
            }
            return false;
        };

        return $searchItems($this->getItems());
    }
    
    /**
     * removeItemByCartItem
     * 
     * @param CartItemInterface $cartItem
     */
    public function removeItemByCartItem(CartItemInterface $cartItem)
    {
        $searchItems = function($items) use($cartItem, &$searchItems){
            foreach ($items as $key => $item) {
                if($item->getCartItem() && $item->getCartItem()->getId() == $cartItem->getId()){
                    $items->remove($key);
                    return true;
                }
                if($item->hasChildren()){
                    $childMatch = $searchItems($item->getChildren());
                    if($childMatch){
                        return true;
                    }
                }
            }
            return false;
        };

        return $searchItems($this->getItems());
    }
    
    /**
     * getVisitor
     *
     * @return VisitorInterface|null
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * setVisitor
     *
     * @param VisitorInterface|null $visitor
     */
    public function setVisitor(VisitorInterface $visitor = null)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * getDestinationCountry
     */
    public function getDestinationCountry()
    {        
        if (strlen($this->getShippingCountry()) > 0) {
            return $this->getShippingCountry();
        }        

        return $this->getBillingCountry();
    }

    /**
     * getCustomFields
     * 
     * @return ArrayCollection
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
    
    /**
     * setCustomFields
     */
    public function setCustomFields($customFieldValues)
    {
        if(is_array($customFieldValues)){
            $this->customFieldValues = new ArrayCollection($customFieldValues);
        } else if(!$customFieldValues instanceof ArrayCollection){
            throw new \Exception("customFieldValues must be instance of ArrayCollection or an array");
        }
        $this->customFields = $customFieldValues;
        return $this;
    }
    
    /**
     * addCustomField
     */
    public function addCustomField(OrderCustomFieldValueInterface $customFieldValue)
    {
        $this->customFields->add($customFieldValue->setOrder($this));
        return $this;
    }

    /**
     * getCustomField
     *
     * @param string $fieldName
     */
    public function getCustomField($fieldName)
    {
        foreach($this->customFields as $customFieldValue){
            if($customFieldValue->getField()->getFieldName() == $fieldName) {
                return $customFieldValue;
            }
        }
        return false;
    }
    
    /**
     * hasCustomField
     * 
     * @param string $fieldName
     */
    public function hasCustomField($fieldName)
    {
        foreach($this->customFields as $customFieldValue){
            if($customFieldValue->getField()->getFieldName() == $fieldName) {
                return true;
            }
        }
        return false;
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
    public function addMemo(OrderMemoInterface $memo)
    {
        $this->memos->add($memo->setOrder($this));
    
        return $this;
    }
    

    /**
     * getSaveBillingAddress
     */
    public function getSaveBillingAddress()
    {
        return $this->saveBillingAddress;
    }
    
    /**
     * setSaveBillingAddress
     */
    public function setSaveBillingAddress($saveBillingAddress)
    {
        $this->saveBillingAddress = $saveBillingAddress;
        return $this;
    }
    
    /**
     * getSaveShippingAddress
     */
    public function getSaveShippingAddress()
    {
        return $this->saveShippingAddress;
    }
    
    /**
     * setSaveShippingAddress
     */
    public function setSaveShippingAddress($saveShippingAddress)
    {
        $this->saveShippingAddress = $saveShippingAddress;
        return $this;
    }
    

    /**
     * setCustomFieldValues
     */
    public function setCustomFieldValues($customFieldValues)
    {
        $this->customFieldValues = $customFieldValues;
        return $this;
    }
    
    /**
     * getCustomFieldValues
     */
    public function getCustomFieldValues()
    {
        return $this->customFieldValues;
    }
}
