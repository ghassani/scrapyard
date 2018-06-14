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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Security\Authentication\Token\GoogleUserToken;
use Spliced\Component\Commerce\Security\Authentication\Client\GoogleOAuth2Client as OAuth2Client;
use Spliced\Component\Commerce\Event as Events;

/**
 * GoogleAuthenticationProvider
 *
 * Handles authentication of a customer by Google API login
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleAuthenticationProvider implements AuthenticationProviderInterface
{

    protected $google;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                $providerKey
     * @param OAuth2Client          $google
     * @param UserProviderInterface $userProvider
     * UserCheckerInterface $userChecker
     */
    public function __construct($providerKey, OAuth2Client $google, UserProviderInterface $userProvider, UserCheckerInterface $userChecker , EventDispatcherInterface $dispatcher)
    {
        $this->providerKey  = $providerKey;
        $this->google         = $google;
        $this->userProvider = $userProvider;
        $this->userChecker  = $userChecker;
        $this->dispatcher     = $dispatcher;
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
        if ($user instanceof UserInterface) {
            $newToken = new GoogleUserToken($user, $user->getRoles());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        try {
            if ($accessToken = $this->google->exchangeAuthTokenForAccessToken($token->getAuthCode())) {

                $newToken = $this->createAuthenticatedToken($accessToken);
                $newToken->setAttributes($token->getAttributes());

                return $newToken;
            }
        } catch (AuthenticationException $failed) {
            throw $failed;
        } catch (\Exception $failed) {
            throw $failed;
            throw new AuthenticationException($failed->getMessage(), null, $failed->getCode(), $failed);
        }

        throw new AuthenticationException('The Google user could not be retrieved from the session.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof GoogleUserToken;
    }

    /**
     * {@inheritDoc}
     */
    private function createAuthenticatedToken(array $accessToken)
    {

        try {
            $userProfile = $this->google->getUserProfile();

            $user = $this->userProvider->getRepository()->findOneByGoogleIdOrEmail(
                $userProfile['result']['id'],
                $userProfile['result']['email']
            );

            $this->userChecker->checkPostAuth($user);

            $this->dispatcher->dispatch(Events\Event::EVENT_SECURITY_GOOGLE_LOGIN, new Events\GoogleLoginEvent($user, $userProfile));

            $this->userProvider->update($user);

        } catch (UsernameNotFoundException $e) {
            $user = $this->createUser($userProfile);
        }

        if (!$user instanceof SecurityUserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }

        return new GoogleUserToken($user, $user->getRoles(), null, $accessToken['result']['access_token']);
    }

    /**
     * createUser
     *
     * @param array $userProfile
     */
    public function createUser(array $userProfile)
    {
        $className = $this->userProvider->getClass();
        $user = new $className;

        $user->setEmail($userProfile['result']['email'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->setForcePasswordReset(true)
          ->addRoles(array('ROLE_GOOGLE','ROLE_USER'));

        $this->dispatcher->dispatch(Events\Event::EVENT_SECURITY_GOOGLE_LOGIN_CREATE_USER, new Events\GoogleLoginEvent($user, $userProfile));

        return $this->userProvider->create($user);
    }

}
