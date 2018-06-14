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

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\UserInterface;
use Spliced\Component\Commerce\Event as Events;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * CustomerEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerEventListener
{
    protected $securityContext;
    protected $em;

    /**
     * Constructor
     *
     * @param UserProviderInterface $customerManager
     * @param ConfigurationManager $configurationManager
     * @param RouterInterface $router, 
     * @param SecurityContext $securityContext, 
     * @param Session $session,
     * @param Swift_Mailer $mailer, 
     * @param EngineInterface $templating
     */
    public function __construct(UserProviderInterface $customerManager,
        ConfigurationManager $configurationManager, 
        RouterInterface $router, 
        SecurityContext $securityContext, 
        Session $session,
        \Swift_Mailer $mailer, 
        EngineInterface $templating
    )
    {
        $this->customerManager = $customerManager;
        $this->configurationManager = $configurationManager;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->templating = $templating;
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
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getSession
     * 
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * getRouter
     * 
     * @return Router
     */
    protected function getRouter()
    {
        return $this->router;
    }
    
    /**
     * getSecurityContext
     * 
     * @return SecutityContext
     */
    protected function getSecurityContext()
    {
         return $this->securityContext;
    }
    
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->configurationManager->getEntityManager();    
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
     * onRegistrationStart
     * 
     * This event is fired when the registration page is loaded
     * and the current user is not logged in
     * 
     * @param RegistrationStartEvent $event
     */
    public function onRegistrationStart(Events\RegistrationStartEvent $event)
    {
    
    }
    
    /**
     * onRegistrationComplete
     *
     * This event is fired after registration has been determined as 
     * successful and the customer has been saved to the database
     * 
     * @param RegistrationCompleteEvent $event
     */
    public function onRegistrationComplete(Events\RegistrationCompleteEvent $event)
    {
        // send out notification if we have an email address, in some cases like
        // logging in through twitter, email addresses are not provided so let's
        // not notify the customer
        if (!$event->getCustomer()->getEmail()) {
            return;
        }
        
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.sales.email.new_account.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.noreply'))
        ->setTo($event->getCustomer()->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.new_account', 'SplicedCommerceBundle:Email:new_account.html.twig'), array(
            'user' => $event->getCustomer()
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.new_account_plain', 'SplicedCommerceBundle:Email:new_account.txt.twig'), array(
            'user' => $event->getCustomer()
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
    
        $this->getMailer()->send($notificationMessage);
    }
    
    /**
     * onKernelRequest
     * 
     * This event is fired on every master request.
     * 
     * It's main role is to check the current user and see
     * if we need to collect additional information from them
     * or if they need to reset their password.
     * 
     * We can determine this and then redirect the response as needed
     * 
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType() || ! $this->getSession()->getId()) {
            return; // "subrequest"
        }
         
        $requestedRoute = $event->getRequest()->get('_route');

        if ($this->securityContext->getToken() && $this->securityContext->isGranted('ROLE_USER') ) {
            $customer = $this->securityContext->getToken()->getUser();
            
            if($customer instanceof UserInterface) {
                // admin user lets not continue
                return;
            }
            
            // if the user has been set to force email address collection
            if(($customer->getForceCollectEmail() || $customer->getForcePasswordReset()) 
                && !in_array($requestedRoute, array('commerce_account_finalize_registration'))
                && !preg_match('/login\_check$/',$requestedRoute)){
                
                $event->setResponse(new RedirectResponse(
                    $this->router->generate('commerce_account_finalize_registration')
                ));
                return;
            }
        }
    }
    

    /**
     * onPasswordReset
     */
    public function onPasswordReset(Events\PasswordResetEvent $event)
    {
        $user = $event->getUser();
    
        $user->setPlainPassword($event->getNewPassword())
        ->setConfirmationToken(null);
    
        $this->getCustomerManager()->updatePassword($user);
    
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    
    /**
     * onPasswordResetRequest
     */
    public function onPasswordResetRequest(Events\PasswordResetRequestEvent $event)
    {
        $user = $event->getUser();
    
        $this->getCustomerManager()->updateConfirmationToken($user);
    
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.store.email.password_reset.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.from'))
        ->setTo($user->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.password_reset', 'SplicedCommerceBundle:Email:password_reset.html.twig'), array(
                'user' => $user,
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.password_reset_plain', 'SplicedCommerceBundle:Email:password_reset.txt.twig'), array(
                'user' => $user,
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
    
        $this->getMailer()->send($notificationMessage);
    }
    
    /**
     * onForcePasswordReset
     */
    public function onForcePasswordReset(Events\ForcePasswordResetEvent $event)
    {
        $user = $event->getUser();
    
        $user->setPlainPassword($event->getNewPassword())
        ->setConfirmationToken(null)
        ->setForcePasswordReset(false);
    
        $this->getCustomerManager()->updatePassword($user);
    
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    
    /**
     * onForcePasswordResetRequest
     */
    public function onForcePasswordResetRequest(Events\ForcePasswordResetRequestEvent $event)
    {
        $user = $event->getUser();
    
        $this->getCustomerManager()->updateSalt($user);
        $this->getCustomerManager()->updateConfirmationToken($user);
    
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.store.email.force_password_reset.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.from'))
        ->setTo($user->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.force_password_reset', 'SplicedCommerceBundle:Email:force_password_reset.html.twig'), array(
                'user' => $user,
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.force_password_reset_plain', 'SplicedCommerceBundle:Email:force_password_reset.txt.twig'), array(
                'user' => $user,
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
    
        $this->getMailer()->send($notificationMessage);
    }

}
