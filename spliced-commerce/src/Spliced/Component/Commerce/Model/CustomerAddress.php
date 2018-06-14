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
 * CustomerAddress
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="customer")
 */
abstract class CustomerAddress implements CustomerAddressInterface
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
     * @var string $addressLabel
     *
     * @ORM\Column(name="address_label", type="string", length=50, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $addressLabel;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=150, nullable=false)
     * @Assert\NotNull(groups={"billing_address"}, message="Required")
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=150, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $lastName;
    /**
     * @var string $attn
     *
     * @ORM\Column(name="attn", type="string", length=150, nullable=true)
     *
     */
    protected $attn;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=150, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $address;

    /**
     * @var string $address2
     *
     * @ORM\Column(name="address2", type="string", length=150, nullable=true)
     */
    protected $address2;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=75, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $city;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="string", length=50, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $state;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(name="zipcode", type="string", length=15, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $zipcode;

    /**
     * @var string $country
     *
     * @ORM\Column(name="country", type="string", length=50, nullable=false)
     * @Assert\NotNull(message="Required", groups={"add_address"})
     */
    protected $country;
    
    /**
     * @var string $phoneNumber
     *
     * @ORM\Column(name="phone_number", type="string", length=50, nullable=true)
     */
    protected $phoneNumber;
    
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
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="addresses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    protected $customer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * __toString
     */
    public function __toString()
    {
        $parts = array();
        if ($this->getAddressLabel()) {
            $parts[] = $this->getAddressLabel();
        }
        $parts[] = trim(sprintf('%s %s %s',$this->getAddress(),$this->getState(),$this->getCountry()));
    
        return implode(' - ',$parts);
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
     * Set addressLabel
     *
     * @param string $addressLabel
     */
    public function setAddressLabel($addressLabel)
    {
        $this->addressLabel = trim($addressLabel);

        return $this;
    }

    /**
     * Get addressLabel
     *
     * @return string
     */
    public function getAddressLabel()
    {
        return $this->addressLabel;
    }

    /**
     * Set attn
     *
     * @param string $attn
     */
    public function setAttn($attn)
    {
        $this->attn = trim($attn);

        return $this;
    }

    /**
     * Get attn
     *
     * @return string
     */
    public function getAttn()
    {
        return $this->attn;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = trim($address);

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = trim($address2);

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = trim($city);

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = trim($state);

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = trim($zipcode);

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = trim($country);

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set customer
     *
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer)
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

    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * setPhoneNumber
     *
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = trim($phoneNumber);
        return $this;
    }
    
    /**
     * getPhoneNumber
     *
     * @return string
    */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
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
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     *
     */
    public function isShippingAddressRequired(ExecutionContext $context)
    {
        if (!$this->getSameAsShipping()) {
            $context->getGraphWalker()->walkReference($this, 'shipping_address_required', $context->getPropertyPath(), true);
        }
    }
}
