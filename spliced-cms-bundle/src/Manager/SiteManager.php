<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Manager;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Spliced\Bundle\CmsBundle\Entity\Site;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Event\SiteEvent;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SiteManager
{
    
    const SESSION_TAG = 'spliced.cms.site';
    
    const SESSION_TAG_ADMIN = 'spliced.cms.site.admin';
    
    protected $currentSite = false;
    
    protected $currentAdminSite = false;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param Session $session
	 * @param ConfigurationManagerInterface $configurationManager
	 * @param EntityManager $em
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(Session $session, ConfigurationManagerInterface $configurationManager, EntityManager $em, EventDispatcherInterface $eventDispatcher)
	{
		$this->session = $session;
		$this->configurationManager = $configurationManager;
		$this->eventDispatcher = $eventDispatcher;
		$this->em = $em;
	}
    /**
     * @return
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    
	/**
	 * findSiteByDomain
	 */
	public function findSiteByDomain($domain)
	{
		return $this->getEntityManager()
		->getRepository('SplicedCmsBundle:Site')
		->findOneByDomain($domain);
	}
	
	/**
	 * findSiteById
	 */
	public function findSiteById($id)
	{
		return $this->getEntityManager()
		  ->getRepository('SplicedCmsBundle:Site')
            ->findOneById($id);
	}
	
	/**
	 * findSiteById
	 */
	public function findSiteBy(array $by)
	{
		return $this->getEntityManager()
		->getRepository('SplicedCmsBundle:Site')
		->findOneBy($by);
	}

    /**
     * getAllSites
     */
    public function getAllSites()
    {
        return $this->getEntityManager()
            ->getRepository('SplicedCmsBundle:Site')
            ->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }

    /**
	 * getCurrentSite
     *
     * Gets the currently viewed website
	 */
	public function getCurrentSite()
	{
        if ($this->currentSite instanceof SiteInterface) {
			return $this->currentSite;
		}
		
		if ($this->getSession()->has(static::SESSION_TAG)) {
            $sessionSite = $this->findSiteById($this->getSession()->get(static::SESSION_TAG));
			if ($sessionSite) {
				$this->setCurrentSite($sessionSite);
				return $sessionSite;
			}
		}
		return false;
	}
	
	/**
	 * setCurrentSite
     *
     * @param SiteInterface $site|null
     *
     * Gets the currently viewed website
	 */
	public function setCurrentSite(SiteInterface $site = null)
	{
        $this->getSession()->set(static::SESSION_TAG, $site ? $site->getId() : null);
		$this->currentSite = $site;
		return $this;
	}

    /**
     * @return bool|SiteInterface
     */
    public function getCurrentAdminSite()
    {
        if ($this->currentAdminSite instanceof SiteInterface) {
            return $this->currentAdminSite;
        }

        if ($this->getSession()->has(static::SESSION_TAG_ADMIN)) {
            $sessionSite = $this->findSiteById($this->getSession()->get(static::SESSION_TAG_ADMIN));
            
            if ($sessionSite) {
                $this->setCurrentAdminSite($sessionSite);
                return $sessionSite;
            }
        }

        if ($this->getConfigurationManager()->get('core.default_admin_site')) {
            
            $defaultConfigSite = $this->findSiteById($this->getConfigurationManager()->get('core.default_admin_site'));
            
            if ($defaultConfigSite) {
                $this->setCurrentAdminSite($defaultConfigSite);
                return $defaultConfigSite;
            }
        }

        return false;
    }

    /**
     * setCurrentAdminSite
     *
     * @param SiteInterface $site|null
     * @return $this
     */
    public function setCurrentAdminSite(SiteInterface $site = null)
    {
        $this->getSession()->set(static::SESSION_TAG_ADMIN, $site ? $site->getId() : null);
        $this->currentAdminSite = $site;
        return $this;
    }
	
	/**
	 * getSession
	 *
	 * @access private
	 * @return Session
	 */
	private function getSession()
	{
		return $this->session;
	}
	
	/**
	 * getEntityManager
	 *
	 * @access private
	 * @return EntityManager
	 */
	private function getEntityManager()
	{
		return $this->em;
	}
	
	/**
	 * getConfigurationManager
	 *
	 * @access private
	 * @return ConfigurationManagerInterface
	 */
	private function getConfigurationManager()
	{
		return $this->configurationManager;
	}

    /**
     * save
     *
     * @param SiteInterface $site
     * @return mixed
     */
    public function save(SiteInterface $site)
    {
        if ($site->getId()) {
            return $this->update($site);
        }
        $this->getEntityManager()->persist($site);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            SiteEvent::SITE_SAVE,
            new SiteEvent($site)
        );
    }
    
    /**
     * update
     *
     * @param SiteInterface $site
     * @return mixed
     */
    public function update(SiteInterface $site)
    {
        if (!$site->getId()) {
            return $this->save($site);
        }
        $this->getEntityManager()->persist($site);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            SiteEvent::SITE_UPDATE,
            new SiteEvent($site)
        );
    }
}