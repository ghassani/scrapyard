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
use Spliced\Component\Commerce\Security\Authentication\Client\PayPalOAuth2Client as OAuth2Client;


/**
 * PayPalUserManager
 *
 * Handles the creation, updating, and deletion of customers from PayPal API
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalUserManager implements UserProviderInterface
{

    protected $paypal;
    protected $userProvider;

    /**
     * Constructor.
     *
     * @param TwitterApiClient      $encoderFactory
     * @param UserProviderInterface $userProvider
     */
    public function __construct(OAuth2Client $paypal, UserProviderInterface $userProvider)
    {
        $this->paypal = $paypal;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($paypalId)
    {
        return $this->userProvider
            ->getRepository()
            ->findOneByPayPalId($paypalId);
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
    public function createUser(array $paypalData)
    {
        /*$className = $this->getClass();
        $user = new $className;

        $user->setEmail('PAYPAL_'.$paypalData['user_id'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->setForcePasswordReset(true)
          ->addRoles(array('ROLE_PAYPAL','ROLE_USER'));

        $profile = $user->getProfile();

        $profile->setTwitterId($twitterData['user_id']);

        $user->setProfile($profile);
        */
        //die('hita');
        $user = new $className;
        return $this->userProvider->create($user);

    }

}
