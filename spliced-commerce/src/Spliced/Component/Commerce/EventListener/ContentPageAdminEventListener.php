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

/**
 * ContentPageAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageAdminEventListener
{
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
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
     * getDocumentManager
     * 
     * @return ObjectManager
     */
    protected function getDocumentManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
    
    /**
     * onContentPageSave
     * 
     * @param ContentPageEvent $event
     */
    public function onContentPageSave(Events\ContentPageEvent $event)
    {
        $page = $event->getContentPage();
        
        $route = $this->getConfigurationManager()
          ->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE)
          ->setPage($page)
          ->setRequestPath($page->getUrlSlug())
          ->setTargetPath('SplicedCommerceBundle:Page:view')
          ->setOptions(array());

        $this->getDocumentManager()->persist($route);

        $page->setRoute($route);
        
        $this->getDocumentManager()->persist($page);
        $this->getDocumentManager()->flush();

    }
    /**
     * onContentPageUpdate
     *
     * @param ContentPageEvent $event
     */
    public function onContentPageUpdate(Events\ContentPageEvent $event)
    {
    
    }
    
    /**
     * onContentPageDelete
     *
     * @param ContentPageEvent $event
     */
    public function onContentPageDelete(Events\ContentPageEvent $event)
    {
    
    }
}