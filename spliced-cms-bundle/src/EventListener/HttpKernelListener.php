<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\EventListener;

use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Spliced\Bundle\CmsBundle\Manager\SiteManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * HttpKernelListener
 * 
 */
class HttpKernelListener
{
	
	/**
	 * Constructor
	 * 
	 * @access public
     * @param ConfigurationManagerInterface $configurationManager
	 * @param SiteManager $siteManager
	 */
	public function __construct(ConfigurationManagerInterface $configurationManager, SiteManager $siteManager, SecurityContextInterface $securityContext)
	{
        $this->configurationManager = $configurationManager;
        $this->siteManager = $siteManager;
        $this->securityContext = $securityContext;
	}

	/**
	 * onKernelRequest
	 * 
	 * @access public
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$siteManager  = $this->getSiteManager();
		$domain       = $event->getRequest()->server->get('SERVER_NAME');

		if (!$siteManager->getCurrentSite()) {
			$site = $siteManager->findSiteByDomain($domain);
			
            if ($site) {
				
                $siteManager->setCurrentSite($site);

			} else if ($this->getConfigurationManager()->has('core.default_admin_site')) {
                
                $defaultSite = $this->getConfigurationManager()->get('core.default_admin_site');
                
                if ($defaultSite) {
                    $site = $siteManager->findSiteById($defaultSite);
                    if ($site) {
                        $siteManager->setCurrentSite($site);
                        return $site;
                    }
                }

            }
		}

        if ($this->getSecurityContext()->isGranted('ROLE_ADMIN')) {
            
            if (!$siteManager->getCurrentAdminSite() && $siteManager->getCurrentSite()) {
                $siteManager->setCurrentAdminSite($siteManager->getCurrentSite());
            } 

        }
	}

	/**
	 * getSiteManager
	 * 
	 * @return SiteManager
	 */
	private function getSiteManager()
	{
		return $this->siteManager;
	}

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManagerInterface
     */
    private function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getHistoryManager
     *
     * @return HistoryManager
     */
    private function getHistoryManager()
    {
        return $this->historyManager;
    }
    
    /**
     * getSecurityContext
     *
     * @return SecurityContext
     */
    private function getSecurityContext()
    {
        return $this->securityContext;
    }
}