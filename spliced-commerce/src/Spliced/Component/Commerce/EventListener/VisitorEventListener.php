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
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Spliced\Component\Commerce\Affiliate\AffiliateManager;
use Spliced\Component\Commerce\Visitor\VisitorManager;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;
use Spliced\Component\Commerce\Logger\Logger;

/**
 * VisitorEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class VisitorEventListener
{
    protected $securityContext;
    protected $em;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param SecurityContext $securityContext
     * @param Session         $session
     * @param Request         $request
     * @param AffiliateManager $affiliateManager
     * @param VisitorManager $visitorManager
     * @param Logger   $logger
     */
    public function __construct(ConfigurationManager $configurationManager, SecurityContext $securityContext, Session $session, AffiliateManager $affiliateManager, VisitorManager $visitorManager, Logger $logger)
    {
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->configurationManager = $configurationManager;
        $this->affiliateManager = $affiliateManager;
        $this->visitorManager = $visitorManager;
        $this->logger = $logger;      
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
     * getVisitorManager
     * 
     * @return VisitorManager
     */
    protected function getVisitorManager()
    {
        return $this->visitorManager;
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
     * getAffiliateManager
     * 
     * @return AffiliateManager
     */
    protected function getAffiliateManager()
    {
        return $this->affiliateManager;
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
     * onKernelTerminate
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        
        $visitor = $this->getVisitorManager()->getCurrentVisitor();
                
        // dont log assets or profiler requests
        if($visitor && ( !preg_match('/\/\_wdt\//i', @$_SERVER['REQUEST_URI']) && !preg_match('/\.(jpg|jpeg|gif|png|js|css|woff|svg|ttf|otf)$/', @$_SERVER['REQUEST_URI']))){
            $this->getVisitorManager()->logRequest($event->getRequest());
        }
    } 
    
    /**
     * onKernelRequest
     * 
     * Handles a visitor, either creating a new one or loading 
     * an existing one, and creating one if none found
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return; // "subrequest"
        }
        
        $visitor = $this->getVisitorManager()->getCurrentVisitor();
        
        if($visitor === false){
            $visitor = $this->getVisitorManager()->createVisitor($event->getRequest());
        }

    }
    
}
