<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\User;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Spliced\Bundle\CommerceBundle\Entity\Customer as User;
use Doctrine\ORM\EntityManager;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Spliced\Component\Commerce\Model\CustomerProfileInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Spliced\Component\Commerce\Event as Events;

/**
 * CustomerUserManager
 *
 * Handles the creation, updating, and deletion of customers.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerUserManager implements UserProviderInterface
{
    /**
    * Constructor.
    *
    * @param ConfigurationManager $configurationManager
    * @param EncoderFactoryInterface $encoderFactory
    * @param EventDispatcher $eventDispatcher
    */
    public function __construct(ConfigurationManager $configurationManager, EncoderFactoryInterface $encoderFactory, EventDispatcher $eventDispatcher)
    {
        $this->configurationManager = $configurationManager;
        $this->encoderFactory = $encoderFactory;
        $this->eventDispatcher = $eventDispatcher;
        
        $this->repository = $configurationManager->getEntityManager()
          ->getRepository($this->getClass());
    }

    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getEncoderFactory
     * 
     * @return EncoderFactoryInterface
     */
    protected function getEncoderFactory()
    {
        return $this->configurationManager->getEntityManager();
    }
    
    /**
     * getEncoder
     * 
     * @param SecurityUserInterface $customer
     */
    protected function getEncoder(SecurityUserInterface $customer)
    {
        return $this->encoderFactory->getEncoder($customer);
    }
    
    /**
     * getEntityManager
     * 
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->configurationManager->getEntityManager();
    }

    /**
     * getRepository
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
    
    /**
     * create
     * 
     * Creates a new customer, returns the saved customer
     * if flushed, returns the persisted customer if not
     * 
     * @param SecurityUserInterface|null $customer
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function create($customer, $flush = true)
    {
        if (! $customer instanceof SecurityUserInterface) {
            $customer = new $this->getClass();
        }

        $this->updatePassword($customer);
        
        $customer->addRole(CustomerInterface::ROLE_DEFAULT)
        ->setEnabled(true);
        
        /*if($customer->requiresConfirmation()){
            // TODO this is currently not fully implemented
            $customer->setLocked(true)
            ->setConfirmationToken(md5(rand(100,2000000)));
        }*/
        
        $this->getEntityManager()->persist($customer);
        
        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
        
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CUSTOMER_REGISTRATION_COMPLETE,
            new Events\RegistrationCompleteEvent($customer)
        );
        
        return $customer;
    }

    /**
     * update
     * 
     * Updates an existing customer
     * 
     * @param SecurityUserInterface $customer
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function update(SecurityUserInterface $customer, $flush = true)
    {
        $this->getEntityManager()->persist($customer);
        
        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
        
        return $customer;
    }
    
    /**
     * updatePassword
     * 
     * Updates a customers password
     * 
     * @param SecurityUserInterface $customer
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will not be persisted otherwise, you 
     *                      will need to persist and flush the entity if
     *                      you set set or keep the default value of false.
     */
    public function updatePassword(SecurityUserInterface $customer, $flush = false)
    {
        if (0 !== strlen($password = $customer->getPlainPassword())) {
            $encoder = $this->getEncoder($customer);
            $customer->setPassword($encoder->encodePassword($password, $customer->getSalt()));
            $customer->eraseCredentials();
        }
        
        if (true === $flush) {
            $this->getEntityManager()->persist($customer);
            $this->getEntityManager()->flush();
        }
        
        return $customer;
    }
    
    /**
     * updateSalt
     * 
     * Updates a customers password salt
     * 
     * @param SecurityUserInterface $customer
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will not be persisted otherwise, you 
     *                      will need to persist and flush the entity if
     *                      you set set or keep the default value of false.
     */
    public function updateSalt(SecurityUserInterface $customer, $flush = false)
    {
        if(!$customer->getSalt()){
            $customer->setSalt(md5($customer->getEmail()));
        }
        
        if (true === $flush) {
            $this->getEntityManager()->persist($customer);
            $this->getEntityManager()->flush();
        }
        
        return $customer;
    }

    /**
     * updateConfirmationToken
     * 
     * Updates a customers confirmation token
     * 
     * @param SecurityUserInterface $customer
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will not be persisted otherwise, you 
     *                      will need to persist and flush the entity if
     *                      you set set or keep the default value of false.
     */
    public function updateConfirmationToken(SecurityUserInterface $customer)
    {
        $customer->setConfirmationToken(md5(rand(1000,100000)));
        

        if (true === $flush) {
            $this->getEntityManager()->persist($customer);
            $this->getEntityManager()->flush();
        }
        
        return $customer;
    }
    
    /**
     * loadUserByUsername
     * 
     * @param string $username 
     */
    public function loadUserByUsername($username)
    {
        return $this->loadUserByEmail($username);
    }

    /**
     * loadUserByEmail
     * 
     * @param string $email 
     */
    public function loadUserByEmail($email)
    {
        return $this->repository->findOneByEmail($email);
    }

    /**
     * loadUserBy
     * 
     * @param array $criteria
     */
    public function loadUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * loadUserByConfirmationToken
     * 
     * @param string $token
     * 
     * @return 
     */
    public function loadUserByConfirmationToken($token)
    {
        return $this->repository->findOneByConfirmationToken($token);
    }

    /**
     * refreshUser
     *
     * @param  SecurityUserInterface $customer
     * 
     * @return SecurityUserInterface
     */
    public function refreshUser(SecurityUserInterface $customer)
    {
        $class = $this->getClass();
        if (!$customer instanceof $class) {
            throw new UnsupportedUserException('Account is not supported.');
        }

        if (!$customer instanceof SecurityUserInterface) {
            throw new UnsupportedUserException(sprintf('Expected an instance of use Symfony\Component\Security\Core\User\UserInterface, but got "%s".', get_class($customer)));
        }

        $refreshedUser = $this->repository->findOneById($customer->getId());

        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $customer->getId()));
        }

        return $refreshedUser;
    }

    /**
     * supportsClass
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     * getClass
     */
    public function getClass()
    {
        return $this->getConfigurationManager()
          ->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER);
    }

}
