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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Spliced\Component\Commerce\Security\Authentication\Token\FacebookUserToken;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Event as Events;

/**
 * FacebookAuthenticationProvider
 *
 * Handles authentication of a customer by Facebook API login
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var \BaseFacebook
     */
    protected $facebook;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                        $providerKey
     * @param BaseFacebook                  $facebook
     * @param UserProviderInterface         $userProvider
     * @param UserCheckerInterface          $userChecker
     * @param ContainerAwareEventDispatcher $dispatcher
     */
    public function __construct($providerKey, \BaseFacebook $facebook, UserProviderInterface $customerManager, UserCheckerInterface $userChecker, EventDispatcher $dispatcher)
    {
        if (!$customerManager instanceof UserProviderInterface) {
            throw new \InvalidArgumentException('$customerManager must implement UserManagerInterface if $createIfNotExists is true.');
        }

        $this->providerKey = $providerKey;
        $this->facebook = $facebook;
        $this->customerManager = $customerManager;
        $this->userChecker = $userChecker;
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

        $customer = $token->getUser();

        if ($customer instanceof UserInterface) {
            $this->userChecker->checkPostAuth($customer);
            $newToken = new FacebookUserToken($this->providerKey, $customer, $customer->getRoles(), $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        if (!is_null($token->getAccessToken())) {
              $this->facebook->setAccessToken($token->getAccessToken());
        }

        if ($uid = $this->facebook->getUser()) {
            $newToken = $this->createAuthenticatedToken($uid, $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        throw new AuthenticationException('The Facebook user could not be retrieved from the session.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken && $this->providerKey === $token->getProviderKey();
    }

    /**
     * {@inheritDoc}
     */
    protected function createAuthenticatedToken($uid, $accessToken = null)
    {
        if (null === $this->customerManager) {
            return new FacebookUserToken($this->providerKey, $uid, array(), $accessToken);
        }

        try {
            $facebookUserProfile = $this->facebook->api('/me');

            $customer = $this->customerManager->getRepository()
              ->findOneByFacebookIdOrEmail($uid, $facebookUserProfile['email']);

            $this->userChecker->checkPostAuth($customer);

            $this->dispatcher->dispatch(
                Events\Event::EVENT_SECURITY_FACEBOOK_LOGIN, 
                new Events\FacebookLoginEvent($customer, $facebookUserProfile)
            );

            $this->customerManager->update($customer);

        } catch (UsernameNotFoundException $e) {
            $customer = $this->createUser($facebookUserProfile);
        } catch (\FacebookApiException $e) {
            throw new AuthenticationException('Could Not Get User Facebook Details. Was permission granted?');
        }

        if (!$customer instanceof UserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }

        return new FacebookUserToken($this->providerKey, $customer, $customer->getRoles(), $accessToken);
    }

    /**
     * createUser
     *
     * @param array $userProfile
     */
    public function createUser(array $facebookUserProfile)
    {
        $className = $this->customerManager->getClass();
        $customer = new $className;

        $customer->setEmail($facebookUserProfile['email'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->addRoles(array('ROLE_FACEBOOK','ROLE_USER'));

        $this->dispatcher->dispatch(
            Events\Event::EVENT_SECURITY_FACEBOOK_LOGIN_CREATE_USER, 
            new Events\FacebookLoginEvent($customer, $facebookUserProfile)
        );

        return $this->customerManager->create($customer);
    }

}
