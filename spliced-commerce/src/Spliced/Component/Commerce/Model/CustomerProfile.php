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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CustomerProfile
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_profile")
 * @ORM\Entity
 */
abstract class CustomerProfile implements CustomerProfileInterface
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
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=150, nullable=true)
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=150, nullable=true)
     */
    protected $lastName;

    /**
     * @var string $yahooId
     *
     * @ORM\Column(name="yahoo_id", type="string", length=255, nullable=true)
     */
    protected $yahooId;

    /**
     * @var string $googleId
     *
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId;

    /**
     * @var string $facebookId
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string $twitterId
     *
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitterId;

    /**
     * @var string $address
     *
     * @ORM\Column(name="newsletter_subscriber", type="boolean", nullable=true)
     */
    protected $newsletterSubscriber;

    /**
     * @ORM\OneToOne(targetEntity="Customer", inversedBy="profile")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToOne(targetEntity="CustomerAddress")
     * @ORM\JoinColumn(name="prefered_billing_address", referencedColumnName="id")
     */
    protected $preferedBillingAddress;

    /**
     * @ORM\OneToOne(targetEntity="CustomerAddress")
     * @ORM\JoinColumn(name="prefered_shipping_address", referencedColumnName="id")
     */
    protected $preferedShippingAddress;
    

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
     * Set customer
     *
     * @param Customer $customer
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
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
     * getFirstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     *  setFirstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }
    
    /**
     *  setLastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }
    
    /**
     * getLastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * setNewsletterSubscriber
     */
    public function setNewsletterSubscriber($newsletterSubscriber)
    {
        $this->newsletterSubscriber = $newsletterSubscriber;
    
        return $this;
    }
    
    /**
     * getNewsletterSubscriber
     */
    public function getNewsletterSubscriber()
    {
        return $this->newsletterSubscriber;
    }
    
    /**
     *
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s',$this->getFirstName(),$this->getLastName()));
    }
    
    /**
     *
     */
    public function getPreferedBillingAddress()
    {
        return $this->preferedBillingAddress;
    }
    
    /**
     *
     */
    public function setPreferedBillingAddress(CustomerAddress $address = null)
    {
        $this->preferedBillingAddress = $address;
    
        return $this;
    }
    
    /**
     *
     */
    public function getPreferedShippingAddress()
    {
        return $this->preferedShippingAddress;
    }
    
    /**
     *
     */
    public function setPreferedShippingAddress(CustomerAddress $address = null)
    {
        $this->preferedShippingAddress = $address;
    
        return $this;
    }
    
    /**
     *
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }
    
    /**
     *
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    
        return $this;
    }
    
    /**
     *
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }
    
    /**
     *
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;
    
        return $this;
    }
    
    /**
     *
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }
    
    /**
     *
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    
        return $this;
    }
    
    /**
     *
     */
    public function getYahooId()
    {
        return $this->yahooId;
    }
    
    /**
     *
     */
    public function setYahooId($yahooId)
    {
        $this->yahooId = $yahooId;
    
        return $this;
    }
}