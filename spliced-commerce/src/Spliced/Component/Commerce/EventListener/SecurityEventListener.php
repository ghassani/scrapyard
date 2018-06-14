<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Spliced\Component\Commerce\Event as Events;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Spliced\Component\Commerce\Security\LoginManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\CustomerAddressInterface;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Spliced\Component\Commerce\Logger\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * SecurityEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */ 
class SecurityEventListener
{
    public function __construct(UserProviderInterface $userManager, LoginManager $loginManager, ConfigurationManager $configurationManager, EntityManager $em, \Swift_Mailer $mailer, EngineInterface $templating, Logger $logger)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->configurationManager = $configurationManager;
        $this->loginManager = $loginManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->logger = $logger;
    }

    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }
    
    /**
     * getLogger
     * 
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }
    
    /**
     * getUserManager
     * 
     * @return UserProviderInterface
     */
    protected function getUserManager()
    {
        return $this->userManager;
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
     * getLoginManager
     * 
     * @return LoginManager
     */
    protected function getLoginManager()
    {
        return $this->loginManager;
    }
    
    /**
     * getMailer
     * 
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }
    
    /**
     * getTemplating
     * 
     * @return TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;
    }
    





    
    /**
     * onLoginSuccess
     */
    public function onLoginSuccess(Events\LoginRegisterStartEvent $event)
    {

    }

    /**
     * onPayPalLogin
     */
    public function onPayPalLogin(Events\PayPalLoginEvent $event)
    {
        $customer = $event->getUser();
        $payPalProfile = $event->getPayPalProfile();
        $profile = $customer->getProfile();

        if (!$customer->hasRole('ROLE_PAYPAL') ) {
            $customer->addRole('ROLE_PAYPAL');
        }

        if (!$profile->getFirstName()||!$profile->getLastName()) {
            $profile->setFirstName($payPalProfile['result']['given_name'])
            ->setLastName($payPalProfile['result']['family_name']);
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onPayPalLoginCreateUser
     */
    public function onPayPalLoginCreateUser(Events\PayPalLoginEvent $event)
    {
        $customer         = $event->getUser();
        $payPalProfile  = $event->getPayPalProfile();
        $profile         = $customer->getProfile();

        $profile->setFirstName($payPalProfile['result']['given_name'])
        ->setLastName($payPalProfile['result']['family_name']);

        // if we get an address back, lets add it for the customer
        // to easily select it during checkout
        if (isset($payPalProfile['result']['address'])) {
            $addressClass = $this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER_ADDRESS);
            $customerAddress = new $addressClass;

            if (!$customerAddress instanceof CustomerAddressInterface) {
                throw new \RuntimeException(sprintf('Customer address must be instance of CustomerAddressInterface, but got `%s`',get_class($customerAddress)));
            }

            $customerAddress->setCustomer($customer)
              ->setFirstName($payPalProfile['result']['given_name'])
              ->setLastName($payPalProfile['result']['family_name'])
              ->setAddressLabel('PayPal Address')
              ->setAddress($payPalProfile['result']['address']['street_address'])
              ->setCity($payPalProfile['result']['address']['locality'])
              ->setState($payPalProfile['result']['address']['region'])
              ->setZipcode($payPalProfile['result']['address']['postal_code'])
              ->setCountry($payPalProfile['result']['address']['country']);

            $customer->addAddress($customerAddress);
        }

        $customer->setProfile($profile);
    }

    /**
     * onFacebookLogin
     */
    public function onFacebookLogin(Events\FacebookLoginEvent $event)
    {
        $customer = $event->getUser();
        $facebookProfile = $event->getFacebookProfile();

        if ($customer->getProfile() && ! $customer->getProfile()->getFacebookId()) {
            $customer->getProfile()->setFacebookId($facebookProfile['id']);
        }

        if (!$customer->hasRole('ROLE_FACEBOOK')) {
            $customer->addRole('ROLE_FACEBOOK');
        }

        if (!$customer->getProfile()->getFirstName()) {
            $customer->getProfile()->setFirstName($facebookProfile['first_name']);
        }

        if (!$customer->getProfile()->getLastName()) {
            $customer->getProfile()->setLastName($facebookProfile['last_name']);
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onFacebookLoginCreateUser
     */
    public function onFacebookLoginCreateUser(Events\FacebookLoginEvent $event)
    {
        $user = $event->getUser();
        $facebookProfile = $event->getFacebookProfile();

        $user->getProfile()->setFacebookId($facebookProfile['id'])
        ->setFirstName($facebookProfile['first_name'])
        ->setLastName($facebookProfile['last_name']);
    }

    /**
     * onTwitterLogin
     */
    public function onTwitterLogin(Events\TwitterLoginEvent $event)
    {
        $customer = $event->getUser();
        $twitterProfile = $event->getTwitterProfile();

        if (!$customer->getProfile()->getTwitterId()) {
            $customer->getProfile()->setTwitterId($twitterProfile['user_id']);
        }

        if (!$customer->hasRole('ROLE_TWITTER')) {
            $customer->addRole('ROLE_TWITTER');
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onTwitterLogin
     */
    public function onTwitterLoginCreateUser(Events\TwitterLoginEvent $event)
    {
        $customer = $event->getUser();
        $twitterProfile = $event->getTwitterProfile();

        $customer->getProfile()->setTwitterId($twitterProfile['user_id']);
    }

    /**
     * onGoogleLogin
     */
    public function onGoogleLogin(Events\GoogleLoginEvent $event)
    {

        $customer = $event->getUser();
        $googleProfile = $event->getGoogleProfile();

        if (!$customer->getProfile()->getGoogleId()) {
            $customer->getProfile()->setGoogleId($googleProfile['result']['id']);
        }

        if (!$customer->hasRole('ROLE_GOOGLE')) {
            $customer->addRole('ROLE_GOOGLE');
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onGoogleLoginCreateUser
     */
    public function onGoogleLoginCreateUser(Events\GoogleLoginEvent $event)
    {

        $customer = $event->getUser();
        $googleProfile = $event->getGoogleProfile();

        $customer->getProfile()->setGoogleId($googleProfile['result']['id'])
        ->setFirstName($googleProfile['result']['given_name'])
        ->setLastName($googleProfile['result']['family_name']);

    }

    
}