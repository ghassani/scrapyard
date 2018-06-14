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

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Security\Authentication\Token\PayPalUserToken;
use Spliced\Component\Commerce\Security\Authentication\Client\PayPalOAuth2Client as OAuth2Client;
use Spliced\Component\Commerce\Event as Events;

/**
 * PayPalAuthenticationProvider
 *
 * Handles authentication of a customer by PayPal API
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalAuthenticationProvider implements AuthenticationProviderInterface
{

    protected $paypal;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                $providerKey
     * @param OAuth2Client          $paypal
     * @param UserProviderInterface $userProvider
     * UserCheckerInterface $userChecker
     */
    public function __construct($providerKey, OAuth2Client $paypal, UserProviderInterface $userProvider, UserCheckerInterface $userChecker, EventDispatcher $dispatcher)
    {
        $this->providerKey = $providerKey;
        $this->paypal = $paypal;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->dispatcher = $dispatcher;
    }

    /**
     * getPaypal
     * 
     * @return OAuth2Client
     */
    protected function getPaypal()
    {
        return $this->paypal;
    }
    
    /**
     * getUserChecker
     */
    protected function getUserChecker()
    {
        return $this->userChecker;
    }
    
    /**
     * getUserProvider
     */
    protected function getUserProvider()
    {
        return $this->userProvider;
    }
    
    /**
     * getEventDispatcher
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
        
        $user = $token->getUser();

        if ($user instanceof SecurityUserInterface) {
            $newToken = new PayPalUserToken($user, $user->getRoles());
            $newToken->setAttributes($token->getAttributes());
            die('a');
            return $newToken;
        }

        try {
            if ($accessToken = $this->getPaypal()->exchangeAuthTokenForAccessToken($token->getAuthCode())) {
                $this->getPaypal()->setAccessToken($accessToken['result']['access_token']);
                $newToken = $this->createAuthenticatedToken($token->getAuthCode(), $accessToken);
                $newToken->setAttributes($token->getAttributes());
                return $newToken;
            }
            
        } catch (AuthenticationException $failed) {
            throw $failed;
        } catch (\Exception $failed) {
            throw $failed;
            throw new AuthenticationException($failed->getMessage(), null, $failed->getCode(), $failed);
        }

        throw new AuthenticationException('The PayPal user could not be retrieved from the session.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof PayPalUserToken;
    }

    /**
     * {@inheritDoc}
     */
    private function createAuthenticatedToken($authToken, array $accessTokenResponse)
    {

        try {

            $userProfile = $this->getPaypal()->getUserProfile();

            $user = $this->getUserProvider()->getRepository()
              ->findOneByEmail($userProfile['result']['email']);

            $this->getUserChecker()->checkPostAuth($user);

            $this->getEventDispatcher()->dispatch(
                Events\Event::EVENT_SECURITY_PAYPAL_LOGIN, 
                new Events\PayPalLoginEvent($user, $userProfile)
            );
            
            $this->getUserProvider()->update($user);

        } catch (UsernameNotFoundException $e) {
            $user = $this->createUser($userProfile);
        }

        if (!$user instanceof SecurityUserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }

        return new PayPalUserToken($user, $user->getRoles(), $authToken, $accessTokenResponse['result']['access_token']);

    }

    /**
     * createUser
     *
     * @param array $userProfile
     */
    public function createUser(array $userProfile)
    {
        $className = $this->getUserProvider()->getClass();
        $user = new $className;

        $user->setEmail($userProfile['result']['email'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->setForcePasswordReset(true)
          ->addRoles(array('ROLE_PAYPAL','ROLE_USER'));

        $this->getEventDispatcher()->dispatch(
            Events\Event::EVENT_SECURITY_PAYPAL_LOGIN_CREATE_USER, 
            new Events\PayPalLoginEvent($user, $userProfile)
        );

        return $this->getUserProvider()->create($user);
    }

}
