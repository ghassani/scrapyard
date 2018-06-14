<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Category;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * CategoryManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryManager
{
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param EventDispatcherInterface $eventDispatcher
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
     * Creates a new Category for manipulation. You will need to 
     * save the changes made after creation. You can use the save 
     * method to do this.
     * 
     * @return CategoryInterface
     */
    public function create()
    {
        return $this->configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CATEGORY);
    }
    
    /**
     * save
     * 
     * Saves and persists a new category. If an existing category is passed, 
     * this method will just forward to update method.
     * 
     * @param CategoryInterface $category
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function save(CategoryInterface $category, $flush = true)
    {
        if ($category->getId()) { // forward to update, it is not new
            return $this->update($category);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CATEGORY_SAVE,
            new Events\CategorySaveEvent($category)
        );
        
        $this->configurationManager->getEntityManager()->persist($category);
        
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    

    /**
     * update
     * 
     * Updates an existing category. If a new category is passed, 
     * this method will just forward to save method.
     * 
     * @param CategoryInterface $category
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function update(CategoryInterface $category, $flush = true)
    {
        if (!$category->getId()) { // forward to create, it is new
            return $this->save($category);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CATEGORY_UPDATE,
            new Events\CategoryUpdateEvent($category)
        );
            
        $this->configurationManager->getEntityManager()->persist($category);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }

    /**
     * delete
     *
     * @param CategoryInterface $category
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function delete(CategoryInterface $category, $flush = true)
    {
        if (!$category->getId()) {//has never been saved
            return;
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_CATEGORY_DELETE,
            new Events\CategoryDeleteEvent($category)
        );
    
        $this->configurationManager->getEntityManager()->remove($category);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    

}