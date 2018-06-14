<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Visitor;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Affiliate\AffiliateManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Model\VisitorInterface;
use Spliced\Component\Commerce\Logger\Logger;

/**
 * VisitorManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class VisitorManager implements ConfigurableInterface
{
    /** Session Tag to Store Visitor ID */
    const SESSION_TAG_VISITOR_ID = 'commerce.visitor.id';
    
    /**
     * @var VisitorInterface|false
     */
    protected $visitor = false;
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param AffiliateManager $affiliateManager
     * @param Session $session
     */
    public function __construct(ConfigurationManager $configurationManager, AffiliateManager $affiliateManager, Session $session)
    {
        $this->configurationManager = $configurationManager;
        $this->affiliateManager = $affiliateManager;
        $this->session = $session;
    }
    
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
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
     * getEntityManager
     * 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getConfigurationManager()->getEntityManager();
    }

    /**
     * getSession
     * 
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
    
    /**
     * getCurrentVisitor
     * 
     * Gets the current visitor object. Creates one if they are a new visitor
     * 
     * @param bool $forceQuery - Force the method to re-call the database to get a fresh visitor object
     * 
     * @return VisitorInterface
     */
     public function getCurrentVisitor()
     {
        if($this->visitor instanceof VisitorInterface){ // dont re-load if we already have one, unless specified
            return $this->visitor;
        }
        
        $visitorRepository = $this->getEntityManager()
        ->getRepository(
            $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR)
        );
        
        try {
                
            if($this->getCurrentVisitorId()){
                   
                $this->visitor = $visitorRepository->findOneById($this->getCurrentVisitorId());
                    
            } else {
                $this->visitor = $visitorRepository->findOneBySessionId($this->getSession()->getId());
            }
            
            if(!$this->visitor instanceof VisitorInterface){
                throw new NoResultException('Visitor Not Found');
            }
            
    
        } catch (NoResultException $e) {
           return false;
        }

        return $this->visitor;
     }
    
    /**
     * createVisitor
     * 
     * @param Request $request
     * 
     * @return VisitorInterface
     */
    public function createVisitor(Request $request)
    {
        $visitor = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR)
             ->setSessionId($this->getSession()->getId());

        
        $isBot = UserAgentHelper::isBot($request->headers->get('user-agent'));
               
        $visitor->setInitialReferer($this->getRefererSource($request->headers->get('referer')))
          ->setInitialRefererUrl($request->headers->get('referer'))
          ->setUserAgent($request->headers->get('user-agent'))
          ->setIp($request->getClientIp())
          ->setHostName($request->getHost())
          ->setIsBot($isBot);
                
        $this->getEntityManager()->persist($visitor);
        $this->getEntityManager()->flush();
        
        // set the current visitor
        $this->setCurrentVisitorId($visitor->getId()); 
        
        $this->visitor = $visitor;
        
        return $visitor;
    }
    
    /**
     * logRequest
     * 
     * @return VisitorRequest
     */
    public function logRequest(Request $request)
    {
        $visitor = $this->getCurrentVisitor();
        
        if((($visitor->getIsBot() && $this->getOption('log_bot_requests')) || (!$visitor->getIsBot() && $this->getOption('log_visitor_requests')))){
              
            $visitorRequest = $this->getConfigurationManager()
              ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR_REQUEST)
              ->setVisitor($visitor)
              ->setRequestedPath(@$_SERVER['REQUEST_URI'])
              ->setReferer($request->headers->get('referer'));
                            
            $visitor->addRequest($visitorRequest);
            
            $this->getEntityManager()->persist($visitor);
            $this->getEntityManager()->flush();
            return true;
        }
        return false;
    }
    
    /**
     * getCurrentVisitorId
     * 
     * @return int|null
     */
    public function getCurrentVisitorId()
    {
        return $this->getSession()->get(static::SESSION_TAG_VISITOR_ID);
    }
    
    /**
     * setCurrentVisitorId
     * 
     * @param int $visitorId
     */
    public function setCurrentVisitorId($visitorId)
    {
        $this->getSession()->set(static::SESSION_TAG_VISITOR_ID, $visitorId);
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.visitor';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getConfigurationManager()->getByKeyExpression(sprintf('/^%s/',$this->getConfigPrefix()));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOption($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get(sprintf('%s.%s',$this->getConfigPrefix(),$key),$defaultValue);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        return array(
            'log_visitor_requests' => array(
                'type' => 'boolean',
                'value' => true,
                'label' => 'Log Visitor Requests',
                'help' => '',
                'group' => 'Visitor',
                'child_group' => 'Logging',
                'position' => 1,
                'required' => false,
            ),
            'log_bot_requests' => array(
                'type' => 'boolean',
                'value' => true,
                'label' => 'Log Bot Requests',
                'help' => '',
                'group' => 'Visitor',
                'child_group' => 'Logging',
                'position' => 1,
                'required' => false,
            ),
        );
    }    
    

    /**
     * getRefererSource
     */
    public function getRefererSource($url)
    {
        if ($match = $this->getAffiliateManager()->findAffiliateByUrl($url)) {
            
            if(!$this->getAffiliateManager()->getCurrentAffiliateId() 
                && $match->getPriceAdjustmentsOnRefererEntry()){
                
                $this->getAffiliateManager()->setCurrentAffiliateId($match->getId());
                
            }
            
            return $match->getName();
        }

        return null;
    }
}