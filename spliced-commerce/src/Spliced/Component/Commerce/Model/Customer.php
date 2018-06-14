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
 * Customer
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Entity()
 * @ORM\Table(name="customer")
 */
abstract class Customer implements CustomerInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;
    
    /**
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;
    
    /**
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    protected $salt;
    
    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    protected $password;
    
    /**
     * @var string $plainPassword
     */
    protected $plainPassword;
    
    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;
    
    /**
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    protected $confirmationToken;
    
    /**
     *@ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;
    
    /**
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked;
    
    /**
     * @ORM\Column(name="expired", type="boolean")
     */
    protected $expired;
    
    /**
     * @ORM\Column(name="expires_at", type="datetime")
     */
    protected $expiresAt;
    
    /**
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;
    
    /**
     * @ORM\Column(name="credentials_expired", type="boolean")
     */
    protected $credentialsExpired;
    
    /**
     * @ORM\Column(name="credentials_expire_at", type="datetime", nullable=true)
     */
    protected $credentialsExpireAt;
    
    /**
     * @ORM\Column(name="force_password_reset", type="boolean")
     */
    protected $forcePasswordReset;
    
    /**
     * @ORM\Column(name="force_collect_email", type="boolean")
     */
    protected $forceCollectEmail;
    
    /**
     * @ORM\OneToOne(targetEntity="CustomerProfile", mappedBy="customer", cascade={"persist"} )
     */
    protected $profile;
    
    /**
     * @ORM\OneToMany(targetEntity="CustomerAddress", mappedBy="customer", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $addresses;
    
    protected $registerForNewsletter;
    protected $saveAddress;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->roles = array();
        $this->credentialsExpired = false;
        $this->addresses = new ArrayCollection();
        $this->creditCards = new ArrayCollection();
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getEmail();
    }

    /**
     * addRole
     *
     * @param string $role
     *
     * @return Customer
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * addRoles
     *
     * @param array $roles
     *
     * @return Customer
     */
    public function addRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->email,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->email,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id
        ) = $data;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * getSalt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    /**
     * setSalt
     *
     * @return string
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Gets the encrypted password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the plain password. Should not be persisted!
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * getConfirmationToken
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * isAccountNonExpired
     */
    public function isAccountNonExpired()
    {
        if (true === $this->expired) {
            return false;
        }

        if (null !== $this->expiresAt && $this->expiresAt->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    /**
     * isAccountNonLocked
     *
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * isCredentialsNonExpired
     *
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        if (true === $this->credentialsExpired) {
            return false;
        }

        if (null !== $this->credentialsExpireAt && $this->credentialsExpireAt->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    /**
     * isCredentialsExpired
     *
     * @return bool
     */
    public function isCredentialsExpired()
    {
        return !$this->isCredentialsNonExpired();
    }

    /**
     * isEnabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * isExpired
     *
     * @return
     */
    public function isExpired()
    {
        return !$this->isAccountNonExpired();
    }

    /**
     * isLocked
     *
     * @return bool
     */
    public function isLocked()
    {
        return !$this->isAccountNonLocked();
    }

    /**
     * removeRole
     *
     * @param string $role
     *
     * @return Customer
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * setUsername
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        return $this->setEmail($username);
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setCredentialsExpireAt(\DateTime $date = null)
    {
        $this->credentialsExpireAt = $date;

        return $this;
    }

    /**
     * @param boolean $boolean
     *
     * @return User
     */
    public function setCredentialsExpired($boolean)
    {
        $this->credentialsExpired = $boolean;

        return $this;
    }

    /**
     * setEmail
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * setEnabled
     *
     * @param bool $boolean
     *
     * @return Customer
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean) $boolean;

        return $this;
    }

    /**
     * Sets this user to expired.
     *
     * @param Boolean $boolean
     *
     * @return User
     */
    public function setExpired($boolean)
    {
        $this->expired = (Boolean) $boolean;

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setExpiresAt(\DateTime $date = null)
    {
        $this->expiresAt = $date;

        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
    
    /**
     * setPassword
     *
     * @param string $password
     *
     * @return Customer
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * setPlainPassword
     *
     * @param string $password
     *
     * @return Customer
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * setLastLogin
     *
     * @param DateTime $time
     *
     * @return Customer
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * setLocked
     *
     * @param bool $boolean
     *
     * @return Customer
     */
    public function setLocked($boolean)
    {
        $this->locked = $boolean;

        return $this;
    }

    /**
     * setConfirmationToken
     *
     * @param string $confirmationToken
     *
     * @return Customer
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * setPasswordRequestedAt
     *
     * @param DateTime $date
     *
     * @return Customer
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;

        return $this;
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * isPasswordRequestNonExpired
     *
     * @param bool
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
               $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * setRoles
     *
     * @param array $roles
     *
     * @return Customer
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * getForcePasswordReset
     *
     * @return bool
     */
    public function getForcePasswordReset()
    {
         return $this->forcePasswordReset;
    }

    /**
     * setForcePasswordReset
     *
     * @param  bool     $value
     * @return Customer
     */
    public function setForcePasswordReset($value)
    {
         $this->forcePasswordReset = $value;

         return $this;
    }

    /**
     * getForceCollectEmail
     *
     * @return string
     */
    public function getForceCollectEmail()
    {
        return $this->forceCollectEmail;
    }

    /**
     * setForceCollectEmail
     *
     * @param  bool     $value
     * @return Customer
     */
    public function setForceCollectEmail($value)
    {
        $this->forceCollectEmail = $value;

        return $this;
    }

    /**
     * requiresPasswordReset
     *
     * @return bool
     */
    public function requiresPasswordReset()
    {
        return $this->getForcePasswordReset();
    }

    /**
     * requiresEmailCollection
     *
     * @return bool
     */
    public function requiresEmailCollection()
    {
        return $this->getForceCollectEmail();
    }

    /**
     * getProfile
     *
     * @return CustomerProfileInterface
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * setProfile
     *
     * @param CustomerProfileInterface $customerProfile
     */
    public function setProfile(CustomerProfileInterface $customerProfile = null)
    {
        if($customerProfile){
            $customerProfile->setCustomer($this);
        }
        
        $this->customerProfile = $customerProfile;
        return $this;
    }

    /**
     * addAddress
     *
     * @param CustomerAddressInterface $customerAddress
     */
    public function addAddress(CustomerAddressInterface $customerAddress)
    {
        $this->addresses->add($customerAddress->setCustomer($this));
        return $this;
    }

    /**
     * getAddresses
     *
     * @return Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * setAddresses
     *
     * @param Collection $addresses
     *
     * @return Customer
     */
    public function setAddresses(Collection $addresses)
    {
        $this->addresses = $addresses;

        return $this;
    }
    
    /**
     * addCreditCard
     *
     * @param CustomerCreditCardInterface $creditCard
     */
    public function addCreditCard(CustomerCreditCardInterface $creditCard)
    {
        $this->creditCards->add($creditCard->setCustomer($this));
        return $this;
    }
    
    /**
     * getCreditCards
     *
     * @return Collection
     */
    public function getCreditCards()
    {
        return $this->creditCards;
    }
    
    /**
     * setCreditCards
     *
     * @param Collection $creditCards
     *
     * @return Customer
     */
    public function setCreditCards(Collection $creditCards)
    {
        $this->creditCards = $creditCards;
    
        return $this;
    }

    /**
     * isSocialLogin
     */
    public function isSocialLogin()
    {
        foreach (array('ROLE_FACEBOOK','ROLE_TWITTER','ROLE_GOOGLE') as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}
