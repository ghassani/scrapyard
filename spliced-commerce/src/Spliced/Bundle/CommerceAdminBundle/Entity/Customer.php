<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Spliced\Component\Commerce\Model\Customer as BaseCustomer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Spliced\Component\Commerce\Model\CustomerAddressInterface;

/**
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 * @ORM\MappedSuperclass()
 * 
 * @Assert\Callback(methods={"isPasswordValid"}, groups={"new_registration","finalize_registration_password"})
 * @Assert\Callback(methods={"isCheckoutRegistrationPasswordValid"}, groups={"checkout_registration"})
 */
class Customer extends BaseCustomer
{

    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->createProfile();
    }

    /**
     * getDisplayName
     *
     * @return string
     */
      public function getDisplayName()
      {
          if ($this->getProfile() && $this->getProfile()->getFullName()) {
              return $this->getProfile()->getFullName();
          }

          return $this->getUsername();
      }

    /**
     * hasPreferedBillingAddress
     *
     * @return bool
     */
    public function hasPreferedBillingAddress()
    {
        return $this->getProfile() && $this->getProfile()->getPreferedBillingAddress();
    }

    /**
     * hasPreferedShippingAddress
     *
     * @return bool
     */
    public function hasPreferedShippingAddress()
    {
        return $this->getProfile() && $this->getProfile()->getPreferedShippingAddress();
    }

    /**
     * getFirstName
     *
     * @return string
     */
    public function getFirstName()
    {
        if (!$this->profile instanceof CustomerProfile) {
            $this->createProfile();
        }

        return $this->profile->getFirstName();
    }

    /**
     * setFirstName
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        if (!$this->profile instanceof CustomerProfile) {
            $this->createProfile();
        }

        return $this->profile->setFirstName($firstName);
    }

    /**
     * getLastName
     *
     * @return string
     */
    public function getLastName()
    {
        if (!$this->profile instanceof CustomerProfile) {
            $this->createProfile();
        }

        return $this->profile->getLastName();
    }

    /**
     * setLastName
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        if (!$this->profile instanceof CustomerProfile) {
            $this->createProfile();
        }

        return $this->profile->setLastName($lastName);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile()
    {
        if (!$this->profile) {
            $this->createProfile();
        }

        return $this->profile;
    }

    /**
     * createProfile
     *
     * @return Customer
     */
    public function createProfile()
    {
        $this->profile = new CustomerProfile();
        $this->profile->setCustomer($this);

        return $this;
    }

    /**
     * isPasswordValid
     *
     * @param ExecutionContext $context
     */
    public function isPasswordValid(ExecutionContext $context)
    {
        $plainPassword = $this->getPlainPassword();

        if (strlen($plainPassword) < 6) {
            $context->addViolationAt('plainPassword', 'Must be at least 6 characters long.', array(), null);
        }
        /*
        if (!preg_match('/[^A-Z]{1,}/i',$plainPassword)) {
            $context->addViolationAt('plainPassword', 'Must be at least contain at least one A-Z character.', array(), null);
        }*/

    }
    
    /**
     * 
     */
    public function isCheckoutRegistrationPasswordValid(ExecutionContext $context)
    {
        $plainPassword = $this->getPlainPassword();
        
        if ($plainPassword && strlen($plainPassword) < 6) {
            $context->addViolationAt('plainPassword', 'Must be at least 6 characters long.', array(), null);
        }
    }
    
    /**
     * hasSavedAddress
     * 
     * @param CustomerAddressInterface $compareAddress
     */
    public function hasSavedAddress(CustomerAddressInterface $compareAddress)
    {
        if(count($this->addresses)){
            foreach($this->addresses as $address){
                if(strtolower($address->__toString()) == strtolower($compareAddress->__toString())){
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * 
     */
     public function getSaveAddress()
     {
         return $this->saveAddress;
     }
     
     /**
      * 
      */
      public function setSaveAddress($saveAddress)
      {
          $this->saveAddress = $saveAddress;
        return $this;
      }
}
