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
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * SearchEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SearchEventListener
{
    public function __construct(ConfigurationManager $configurationManager, Session $session, SecurityContext $securityContext, EntityManager $em)
    {
        $this->em = $em;
        $this->session = $session;
        $this->securityContext = $securityContext;
        $this->configurationManager = $configurationManager;
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
     * getSecurityContext
     *
     * @return SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->securityContext;
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
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }
        
    /**
     * onSearch
     * 
     * @param Event $event
     */
    public function onSearch(Events\SearchEvent $event)
    {
        $queryString = trim($event->getQueryString());
        
        if(empty($queryString)){
            return;
        }

        try {
        
            $visitor = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR))
            ->findOneBySessionId($this->getSession()->getId());
        } catch (NoResultException $e) { /* do nothing */ }
        

        if(isset($visitor)){
            try {
            
                $searchTermRecord = $this->getEntityManager()
                ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_SEARCH_TERM))
                ->findExistingSearch($visitor, $queryString);

            } catch (NoResultException $e) {
                $searchTermRecord = $this->getConfigurationManager()
                ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_SEARCH_TERM)
                ->setSearchQuery($queryString)
                ->setCreatedAt(new \DateTime());
                
                $searchTermRecord->setVisitor($visitor);
            }
        } else {
            $searchTermRecord = $this->getConfigurationManager()
            ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_SEARCH_TERM)
            ->setSearchQuery($queryString)
            ->setCreatedAt(new \DateTime());
        }
        
        if(isset($searchTermRecord) && is_object($searchTermRecord)) {
            if($this->getSecurityContext()->isGranted('ROLE_USER')) {
                $searchTermRecord->setCustomer($this->getSecurityContext()->getToken()->getUser());
            }
            
            $this->getEntityManager()->persist($searchTermRecord);
            $this->getEntityManager()->flush();
        }
    }
}
