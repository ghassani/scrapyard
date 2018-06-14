<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductManager
 * 
 * Handles the creation, saving, updating, and deletion of products
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductManager
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
     * Creates a new Product for manipulation. You will need to 
     * save the changes made after creation. You can use the save 
     * method to do this.
     * 
     * @return ProductInterface
     */
    public function create()
    {
        return $this->configurationManager
          ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT);
    }
    
    /**
     * save
     * 
     * Saves and persists a new product. If an existing product is passed, 
     * this method will just forward to update method.
     * 
     * @param ProductInterface $product
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function save(ProductInterface $product, $flush = true)
    {
        if ($product->getId()) { // forward to update, it is not new
            return $this->update($product);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_SAVE,
            new Events\ProductSaveEvent($product)
        );
        
        $this->configurationManager->getEntityManager()->persist($product);
        
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    

    /**
     * update
     * 
     * Updates an existing product. If a new product is passed, 
     * this method will just forward to save method.
     * 
     * @param ProductInterface $product
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function update(ProductInterface $product, $flush = true)
    {
        if (!$product->getId()) { // forward to create, it is new
            return $this->save($product);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_UPDATE,
            new Events\ProductUpdateEvent($product)
        );

            
        $this->configurationManager->getEntityManager()->persist($product);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }

    /**
     * delete
     *
     * @param ProductInterface $product
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function delete(ProductInterface $product, $flush = true)
    {
        if (!$product->getId()) {//has never been saved
            return;
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_DELETE,
            new Events\ProductDeleteEvent($product)
        );
    
        $this->configurationManager->getEntityManager()->remove($product);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
}