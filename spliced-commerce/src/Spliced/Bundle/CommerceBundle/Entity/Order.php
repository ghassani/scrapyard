<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\ExecutionContextInterface;
use Spliced\Component\Commerce\Model\Order as BaseOrder;
use Spliced\Component\Commerce\Model\OrderShipmentInterface;
use Spliced\Component\Commerce\Model\OrderPaymentInterface;

/**
 * Spliced\CommerceBundle\Entity\Order
 *
 * @ORM\Table(name="customer_order")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceBundle\Repository\OrderRepository")
 * @ORM\MappedSuperclass()
 *  
 * @Assert\Callback(methods={"checkShippingAddress"}, groups={"checkout_step_2"})
 * @Assert\Callback(methods={"checkShippingMethod"}, groups={"checkout_step_3"})
 * @Assert\Callback(methods={"checkPaymentMethod"}, groups={"checkout_step_4"})
 * @Assert\Callback(methods={"checkCustomFields"}, groups={"checkout_custom_fields"})
 */
class Order extends BaseOrder
{
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_1"})
     * @Assert\Email(
     *     message = "Invalid email.",
     *     checkMX = false,
     *     groups = {"checkout_step_1"}
     * )
     */
    protected $email;
    
    /**
     * @var string $billingPhoneNumber
     *
     * @ORM\Column(name="billing_phone_number", type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingPhoneNumber;
        
    /**
     * @var string $billingFirstName
     *
     * @ORM\Column(name="billing_first_name", type="string", length=150, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_1","checkout_step_2"})
     */
    protected $billingFirstName;
    
    /**
     * @var string $billingLastName
     *
     * @ORM\Column(name="billing_last_name", type="string", length=150, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_1","checkout_step_2"})
     */
    protected $billingLastName;
    
    /**
     * @var string $billingAddress
     *
     * @ORM\Column(name="billing_address", type="string", length=150, nullable=false)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingAddress;
    
    /**
     * @var string $billingCity
     *
     * @ORM\Column(name="billing_city", type="string", length=75, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingCity;
    
    /**
     * @var string $billingState
     *
     * @ORM\Column(name="billing_state", type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingState;
    
    /**
     * @var string $billingZipcode
     *
     * @ORM\Column(name="billing_zipcode", type="string", length=15, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingZipcode;
    
    /**
     * @var string $billingCountry
     *
     * @ORM\Column(name="billing_country", type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Required", groups={"checkout_step_2"})
     */
    protected $billingCountry;
    
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
     * @Assert\NotNull(message="Required", groups={"checkout_step_3"})
     */
    protected $shipment;
    
    /**
     * @ORM\OneToOne(targetEntity="OrderPayment", mappedBy="order", cascade={"persist"})
     * @Assert\NotNull(message="Required", groups={"checkout_step_4"})
     */
    protected $payment;
    
    
    /**
     * checkShippingAddress
     *
     * @param ExecutionContext $context
     */
    public function checkShippingAddress(ExecutionContext $context)
    {
        /*if (!empty()) {
         $context->getGraphWalker()->walkReference($this, 'checkout_step_2_alternate_shipping', $context->getPropertyPath(), true);
        }*/
    }
    
    /**
     * checkCustomFields
     * 
     * @param ExecutionContext $context
     */
    public function checkCustomFields(ExecutionContext $context){
        $customFieldValues = $this->getCustomFieldValues();
        foreach($customFieldValues as $fieldName => $fieldValue){
            $fieldValue = trim($fieldValue);
            if(isset($customFieldValues[$fieldName.'_params']) && $params = unserialize($customFieldValues[$fieldName.'_params'])){
                if($params['required'] && empty($fieldValue)){
                    $context->addViolationAt('customFieldValues['.$fieldName.']', $params['required_error'], array(), null);
                    return;
                }
                if(!empty($fieldValue) && $params['pattern'] && !preg_match($params['pattern'], $fieldValue)){
                    $context->addViolationAt('customFieldValues['.$fieldName.']', $params['required_error'], array(), null);
                }
            }
        }
    }
   
    /**
     * checkShippingMethod
     * 
     * @param ExecutionContextInterface
     */
    public function checkShippingMethod(ExecutionContextInterface $context)
    {
        if(!$this->getShipment() instanceof OrderShipmentInterface){
            $context->addViolationAt('shipment[userSelection]', 'Select a Shipping Method', array(), null);
            return;
        }
        $shipmentMethod = $this->getShipment()->getUserSelection();
        if(empty($shipmentMethod)){
            $context->addViolationAt('shipment[userSelection]', 'Select a Shipping Method', array(), null);
        }
    }
    
    /**
     * checkPaymentMethod
     * 
     * @param ExecutionContextInterface
     */
    public function checkPaymentMethod(ExecutionContextInterface $context)
    {
        if(!$this->getPayment() instanceof OrderPaymentInterface){
            $context->addViolationAt('payment', 'Select a Payment Method', array(), null);
            return;
        }
        $paymentMethod = $this->getPayment()->getPaymentMethod();
        if(empty($paymentMethod)){
            $context->addViolationAt('payment', 'Select a Payment Method', array(), null);
        }
    }


}
