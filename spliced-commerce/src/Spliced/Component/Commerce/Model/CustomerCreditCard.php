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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * CustomerCreditCard
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_credit_card")
 * @ORM\Entity
 * @Assert\Callback(methods={
 *     "validateCreditCardNumber",
 *     "validateCreditCardExpiration",
 *     "validateCreditCardCvv"
 * }, groups={"validate_credit_card"})
 */
abstract class CustomerCreditCard implements CustomerCreditCardInterface
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
     * @var string $cardNumber
     *
     * @ORM\Column(name="card_number", type="string", length=255, nullable=true)
     */
    protected $cardNumber;

    /**
     * @var string $cardExpirationMonth
     *
     * @ORM\Column(name="card_expiration_month", type="string", length=2, nullable=true)
     */
    protected $cardExpirationMonth;
    
    /**
     * @var string $cardExpirationYear
     *
     * @ORM\Column(name="card_expiration_year", type="string", length=4, nullable=true)
     */
    protected $cardExpirationYear;

    /**
     * @var integer $cardCvv
     *
     * @ORM\Column(name="card_cvv", type="string", length=4, nullable=true)
     *
     */
    protected $cardCvv;

    /**
     * @var integer $lastFour
     *
     * @ORM\Column(name="last_four", type="string", length=4, nullable=true)
     */
    protected $lastFour;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
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
     * @ORM\OneToOne(targetEntity="OrderPayment", inversedBy="creditCard")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     */
    protected $payment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set cardType
     *
     * @param bigint $cardType
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * Get cardType
     *
     * @return bigint
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set cardExpiration
     *
     * @param string $cardExpiration
     */
    public function setCardExpirationMonth($cardExpirationMonth)
    {
        $this->cardExpirationMonth = $cardExpirationMonth;

        return $this;
    }

    /**
     * Get cardExpirationMonth
     *
     * @return string
     */
    public function getCardExpirationMonth()
    {
        return $this->cardExpirationMonth;
    }
    
        /**
     * Set cardExpirationYear
     *
     * @param string $cardExpirationYear
     */
    public function setCardExpirationYear($cardExpirationYear)
    {
        $this->cardExpirationYear = $cardExpirationYear;

        return $this;
    }

    /**
     * Get cardExpirationYear
     *
     * @return string
     */
    public function getCardExpirationYear()
    {
        return $this->cardExpirationYear;
    }

    /**
     * Set cardCvv
     *
     * @param integer $cardCvv
     */
    public function setCardCvv($cardCvv)
    {
        $this->cardCvv = $cardCvv;

        return $this;
    }

    /**
     * Get cardCvv
     *
     * @return integer
     */
    public function getCardCvv()
    {
        return $this->cardCvv;
    }

    /**
     * Set lastFour
     *
     * @param integer $lastFour
     */
    public function setLastFour($lastFour)
    {
        $this->lastFour = $lastFour;

        return $this;
    }

    /**
     * Get lastFour
     *
     * @return integer
     */
    public function getLastFour()
    {
        return $this->lastFour;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set payment
     * 
     * The reference to the payment used on a non-saved credit card
     * 
     * @param OrderPaymentInterface $payment
     */
    public function setPayment(OrderPaymentInterface $payment = null)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Get payment
     *
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * isEncrypted
     * 
     * Checks for non-numeric characters or if a credit card is greater than 
     * the max to determine if it is an encrypted value or not
     * 
     * @return bool
     */
    public function isEncrypted()
    {
        $creditCard = $this->getCardNumber();
        
        if(empty($creditCard)){
            return false;
        }
        
        if(!preg_match('/^[0-9]{1,}$/', $creditCard)){
            return true;
        }
        
        if(strlen($creditCard) > 16){
            return true; // we can only assume so
        }
        
        return false;
    }
    
    /**
     * validateCreditCardNumber Callback
     *
     * Handles all credit card number validations
     */
    public function validateCreditCardNumber(ExecutionContext $context)
    {
        if($this->isEncrypted()){
            return;
        }
         
        $isValidNumber = false;
        $creditCardNumber = preg_replace('/[^0-9]/i','',$this->getCardNumber());
    
        if (empty($creditCardNumber)) {
            $context->addViolationAt('cardNumber', 'Required', array(), null);
            $isValidNumber = false;
        }
    
        /*if(in_array($creditCardNumber, array('3111111111111111','4111111111111111','5111111111111111','6111111111111111'))){
         $context->addViolationAt('cardNumber', 'Invalid Credit Card Number', array(), null);
        $isValidNumber = false;
        }*/
    
        if (!preg_match(self::REGEXP_VALID_CREDIT_CARD, $creditCardNumber)) {
            $context->addViolationAt('cardNumber', 'Invalid Credit Card Number', array(), null);
            $isValidNumber = false;
        }
    
        $creditCardType = null;
        if ($isValidNumber) {
            foreach(array(
                    'visa' => self::REGEXP_VISA,
                    'mastercard' => self::REGEXP_MASTERCARD,
                    'discover' => self::REGEXP_DISCOVER,
                    'american_express' => self::REGEXP_AMERICAN_EXPRESS,
            ) as $type => $regexp){
                if (preg_match($regexp,$creditCardNumber)) {
                    $creditCardType = $type;
                    break;
                }
            }
    
            if (!$creditCardType) {
                $context->addViolationAt('cardNumber', 'Invalid Credit Card Type', array(), null);
            }
            $this->setCardType($creditCardType);
        }
    }
    
    /**
     * validateCreditCardExpiration Callback
     *
     * Handles all credit card exp validations
     */
    public function validateCreditCardExpiration(ExecutionContext $context)
    {
         
         
        $creditCardNumberExpirationYear = preg_replace('/[^0-9]/i','',$this->getCardExpirationYear());
        $creditCardNumberExpirationMonth = preg_replace('/[^0-9]/i','',$this->getCardExpirationMonth());
    
        if (empty($creditCardNumberExpirationYear)){
            $context->addViolationAt('cardExpirationYear', 'Invalid', array(), null);
        }
    
        if (empty($creditCardNumberExpirationMonth)){
            $context->addViolationAt('cardExpirationMonth', 'Invalid', array(), null);
        }
    
        $length = strlen($creditCardNumberExpirationMonth.$creditCardNumberExpirationYear);
    
        if($length > 7){
            $context->addViolationAt('cardExpiration', 'Expiration Date Invalid. Format as MMYY or MMYYYY.', array(), null);
        }
    
    }
    
    /**
     * validateCreditCardCvv Callback
     *
     * Handles all credit card cvv validations
     */
    public function validateCreditCardCvv(ExecutionContext $context)
    {
        $creditCardNumberCvv = preg_replace('/[^0-9]/i','',$this->getCardCvv());
        if (empty($creditCardNumberCvv)) {
            $context->addViolationAt('cardCvv', 'Required', array(), null);
        } else {
            if (strlen($creditCardNumberCvv) < 3 || strlen($creditCardNumberCvv) > 4) {
                $context->addViolationAt('cardCvv', 'Invalid', array(), null);
            }
        }
    }

}
