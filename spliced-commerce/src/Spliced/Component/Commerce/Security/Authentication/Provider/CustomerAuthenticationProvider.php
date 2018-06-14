<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
//use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Event as Events;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * CustomerAuthenticationProvider
 * 
 * Handles authentication of a customer by form login
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerAuthenticationProvider implements AuthenticationProviderInterface
{

    /**
     * @param string                        $providerKey
     * @param UserProviderInterface         $userProvider
     * @param UserCheckerInterface          $userChecker
     * @param ContainerAwareEventDispatcher $dispatcher
     */
    public function __construct(
            $providerKey, 
            UserProviderInterface $customerManager, 
            UserCheckerInterface $userChecker, 
            EncoderFactoryInterface $encoderFactory, 
            EventDispatcherInterface $dispatcher)
    {
        if (!$customerManager instanceof UserProviderInterface) {
            throw new \InvalidArgumentException('$customerManager must implement UserManagerInterface');
        }
        
        $this->providerKey = $providerKey;
        $this->customerManager = $customerManager;
        $this->userChecker = $userChecker;
        $this->encoderFactory = $encoderFactory;
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * getCustomerManager
     * 
     * @return UserProviderInterface
     */
    protected function getCustomerManager()
    {
        return $this->customerManager;
    }
    
    /**
     * getUserChecker
     * 
     * @return UserCheckerInterface
     */
    protected function getUserChecker()
    {
        return $this->userChecker;
    }
    
    /**
     * getEncoderFactory
     * 
     * @return EncoderFactoryInterface
     */
    protected function getEncoderFactory()
    {
        return $this->encoderFactory;
    }
    
    /**
     * getEventDispatcher
     * 
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $username = $token->getUsername();
        
        if (empty($username)) {
            throw new BadCredentialsException('Your email and/or password could not be found or are invalid.');
        }
        
        try {
            $customer = $this->customerManager->loadUserByUsername($username);
            
            if (!$customer) {
               throw new UsernameNotFoundException('User not found'); 
            }
        } catch (UsernameNotFoundException $notFound) {
            throw new BadCredentialsException('Your email and/or password could not be found or are invalid.', 0, $notFound);
        }
        
        if (!$customer instanceof UserInterface) {
            throw new AuthenticationServiceException('Customer must implement UserInterface.');
        }
        
        try {
            
            $this->userChecker->checkPreAuth($customer);
            
            $this->checkAuthentication($customer, $token);
            
            $this->userChecker->checkPostAuth($customer);
            
        } catch (BadCredentialsException $e) {
            
            if ($customer->getForcePasswordReset()) {
                $this->dispatcher->dispatch(
                    Events\Event::EVENT_SECURITY_LOGIN_FORCE_PASSWORD_RESET_REQUEST, 
                    new Events\ForcePasswordResetRequestEvent($customer)
                );
                
                throw new BadCredentialsException(sprintf('Your password must be reset. An email has been sent to %s with further instructions', $customer->getEmail()), 0, $e);
            }
            
            throw new BadCredentialsException('Bad credentials', 0, $e);
        }
        
        $authenticatedToken = new UsernamePasswordToken(
            $customer, 
            $token->getCredentials(), 
            $this->providerKey, 
            $user->getRoles()
        );
        
        $authenticatedToken->setAttributes($token->getAttributes());
        
        return $authenticatedToken;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken && $this->providerKey === $token->getProviderKey();
    }

    /**
     * {@inheritDoc}
     */
    public function checkAuthentication(UserInterface $customer, UsernamePasswordToken $token)
    {
        if (!$this->encoderFactory->getEncoder($customer)->isPasswordValid($customer->getPassword(), $token->getCredentials(), $customer->getSalt())) {
            throw new BadCredentialsException('The presented password is invalid.');
        }
    }
}