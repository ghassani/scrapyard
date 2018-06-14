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
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Event as Events;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * AdminAuthenticationProvider
 * 
 * Handles authentication of an admin by form login
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AdminAuthenticationProvider implements AuthenticationProviderInterface
{

    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                        $providerKey
     * @param UserProviderInterface         $userProvider
     * @param UserCheckerInterface          $userChecker
     * @param ContainerAwareEventDispatcher $dispatcher
     */
    public function __construct(
            $providerKey, 
            UserProviderInterface $userProvider, 
            UserCheckerInterface $userChecker, 
            EncoderFactoryInterface $encoderFactory, 
            EventDispatcher $dispatcher)
    {
        if (!$userProvider instanceof UserProviderInterface) {
            throw new \InvalidArgumentException('The $userProvider must implement UserManagerInterface if $createIfNotExists is true.');
        }
        
        $this->providerKey = $providerKey;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->encoderFactory = $encoderFactory;
        $this->dispatcher = $dispatcher;
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
            $username = 'NONE_PROVIDED';
        }
        
        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $notFound) {
            throw new BadCredentialsException('Your username and/or password could not be found or are invalid.', 0, $notFound);
        }
        
        if (!$user instanceof UserInterface) {
            throw new AuthenticationServiceException('retrieveUser() must return a UserInterface.');
        }
        
        try {
            $this->userChecker->checkPreAuth($user);
            $this->checkAuthentication($user, $token);
            $this->userChecker->checkPostAuth($user);
        } catch (BadCredentialsException $e) {
            throw new BadCredentialsException('Bad credentials', 0, $e);
        }
        
        $authenticatedToken = new UsernamePasswordToken(
            $user, 
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
    public function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        if (!$this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt())) {
            throw new BadCredentialsException('The presented password is invalid.');
        }
    }
}
