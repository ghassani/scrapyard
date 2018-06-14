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

use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Spliced\Component\Commerce\Security\Authentication\Client\GoogleOAuth2Client as OAuth2Client;

/**
 * GoogleUserManager
 *
 * Handles the creation, updating, and deletion of customers from Twitter API
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleUserManager implements UserProviderInterface
{

    protected $google;
    protected $userProvider;

    /**
     * Constructor.
     *
     * @param TwitterApiClient      $encoderFactory
     * @param UserProviderInterface $userProvider
     */
    public function __construct(OAuth2Client $google, UserProviderInterface $userProvider)
    {
        $this->google = $google;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($twitterId)
    {
        return $this->userProvider->getRepository()->findOneByTwitterId($twitterId);
    }

    /**
     * refreshUser
     *
     * @param  SecurityUserInterface $user
     * @return SecurityUserInterface
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        return $this->userProvider->refreshUser($user);
    }

    /**
     *
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     *
     */
    public function getClass()
    {
        return 'Spliced\Bundle\CommerceBundle\Entity\Customer';
    }

    /**
     *
     */
    public function createUser(array $twitterData)
    {
        $className = $this->getClass();
        $user = new $className;

        $user->setEmail('GOOGLE_'.$twitterData['user_id'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->setForcePasswordReset(true)
          ->addRoles(array('ROLE_GOOGLE','ROLE_USER'));

        $profile = $user->getProfile();

        $profile->setTwitterId($twitterData['user_id']);

        $user->setProfile($profile);

        return $this->userProvider->create($user);

    }

}
