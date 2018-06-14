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
use Spliced\Component\Commerce\Security\Authentication\Token\TwitterUserToken;
use Spliced\Component\Commerce\Security\Authentication\Client\TwitterApiClient;
use Spliced\Component\Commerce\Event as Events;

/**
 * TwitterAuthenticationProvider
 *
 * Handles authentication of a customer by Twitter API
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterAuthenticationProvider implements AuthenticationProviderInterface
{
    protected $twitter;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                $providerKey
     * @param BaseFacebook          $facebook
     * @param UserProviderInterface $userProvider
     * UserCheckerInterface $userChecker
     */
    public function __construct($providerKey, TwitterApiClient $twitter, UserProviderInterface $userProvider, UserCheckerInterface $userChecker, EventDispatcher $dispatcher)
    {
        $this->providerKey = $providerKey;
        $this->twitter = $twitter;
        $this->userProvider = $userProvider;
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

        $user = $token->getUser();
        if ($user instanceof SecurityUserInterface) {
            $newToken = new TwitterUserToken($user, null, $user->getRoles());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        try {
            if ($accessToken = $this->twitter->getAccessToken($token->getUser(), $token->getOauthVerifier())) {
                $newToken = $this->createAuthenticatedToken($accessToken);
                $newToken->setAttributes($token->getAttributes());

                return $newToken;
            }
        } catch (\Exception $failed) {
            throw new AuthenticationException($failed->getMessage(), $failed->getCode(), $failed);
        }

        throw new AuthenticationException('The Twitter user could not be retrieved from the session.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof TwitterUserToken;
    }

    /**
     * {@inheritDoc}
     */
    private function createAuthenticatedToken(array $accessToken)
    {

        try {
            $user = $this->userProvider->getRepository()->findOneByTwitterId($accessToken['user_id']);

            $this->userChecker->checkPostAuth($user);

            $this->dispatcher->dispatch(
                Events\Event::EVENT_SECURITY_TWITTER_LOGIN, 
                new Events\TwitterLoginEvent($user, $accessToken)
            );

            $this->userProvider->update($user);

        } catch (UsernameNotFoundException $e) {
            $user = $this->createUser($accessToken);
        }

        if (!$user instanceof SecurityUserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }
        
        return new TwitterUserToken($user, null, $user->getRoles());

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

        $user->setEmail('TWITTER_'.$userProfile['user_id'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->setForcePasswordReset(true)
          ->setForceCollectEmail(true)
          ->addRoles(array('ROLE_TWITTER','ROLE_USER'));

        $this->dispatcher->dispatch(Events\Event::EVENT_SECURITY_TWITTER_LOGIN_CREATE_USER, new Events\TwitterLoginEvent($user, $userProfile));

        return $this->userProvider->create($user);

    }

}
