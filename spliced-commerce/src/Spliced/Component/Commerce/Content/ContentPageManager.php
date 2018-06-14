<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Content;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spliced\Component\Commerce\Model\ContentPageInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * ContentPageManager
 * 
 * Handles the creation, saving, updating, and deletion of content pages
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageManager
{
    
    /**
     * Constructor
     */
    public function __construct(ConfigurationManager $configurationManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->configurationManager = $configurationManager;
        $this->eventDispatcher = $eventDispatcher;
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
     * getEventDispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }    
    
    /**
     * create
     * 
     * Creates a new ContentPage for manipulation. You will need to 
     * save the changes made after creation. You can use the save 
     * method to do this.
     * 
     * @return ContentPageInterface
     */
    public function create()
    {
        return $this->configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT);
    }
    
    /**
     * save
     * 
     * Saves and persists a new content. If an existing content is passed, 
     * this method will just forward to update method.
     * 
     * @param ContentPageInterface $content
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function save(ContentPageInterface $content, $flush = true)
    {
        if ($content->getId()) { // forward to update, it is not new
            return $this->update($content);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CONTENT_PAGE_SAVE,
            new Events\ContentPageSaveEvent($content)
        );
        
        $this->configurationManager->getEntityManager()->persist($content);
        
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    

    /**
     * update
     * 
     * Updates an existing content. If a new content is passed, 
     * this method will just forward to save method.
     * 
     * @param ContentPageInterface $content
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function update(ContentPageInterface $content, $flush = true)
    {
        if (!$content->getId()) { // forward to create, it is new
            return $this->save($content);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CONTENT_PAGE_UPDATE,
            new Events\ContentPageUpdateEvent($content)
        );

        $this->configurationManager->getEntityManager()->persist($content);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }

    /**
     * delete
     *
     * @param ContentPageInterface $content
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function delete(ContentPageInterface $content, $flush = true)
    {
        if (!$content->getId()) {//has never been saved
            return;
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CONTENT_PAGE_DELETE,
            new Events\ContentPageDeleteEvent($content)
        );
    
        $this->configurationManager->getEntityManager()->remove($content);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }

}