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
use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductSpecificationOptionManager
 * 
 * Handles the saving and updating of ProductSpecificationOption's
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSpecificationOptionManager
{

    /**
     * Constructor
     */
    public function __construct(ConfigurationManager $configurationManager, ProductManager $productManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->configurationManager = $configurationManager;
        $this->productManager = $productManager;
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
     * getProductManager
     *
     * @return ProductManager
     */
    protected function getProductManager()
    {
        return $this->productManager;
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
     * Creates a new ProductSpecificationOption for manipulation. You will need to 
     * save the changes made after creation. You can use the saveProductSpecificationOption 
     * method to do this.
     * 
     * @return ProductSpecificationOptionInterface
     */
    public function create()
    {
        return $this->configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION);
    }
    
    /**
     * save
     * 
     * Saves and persists a new product. If an existing product is passed, 
     * this method will just forward to updateProductSpecificationOption method.
     * 
     * @param ProductSpecificationOptionInterface $productSpecificationOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function save(ProductSpecificationOptionInterface $productSpecificationOption, $flush = true)
    {
        if ($productSpecificationOption->getId()) { // forward to update, it is not new
            return $this->update($productSpecificationOption);
        }

        $productSpecificationOption->setKey(strtolower(preg_replace(array('/[^A-Z0-9\s_\-]/i','/\s/'), array('','_'), $productSpecificationOption->getKey())));
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_SPECIFICATION_OPTION_SAVE,
            new Events\ProductSpecificationOptionSaveEvent($productSpecificationOption)
        );
        
        $this->configurationManager->getEntityManager()->persist($productSpecificationOption);
            
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    

    /**
     * update
     * 
     * Updates an existing product. If a new product is passed, 
     * this method will just forward to saveProductSpecificationOption method.
     * 
     * @param ProductSpecificationOptionInterface $productSpecificationOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function update(ProductSpecificationOptionInterface $productSpecificationOption, $flush = true)
    {
        if (!$productSpecificationOption->getId()) { // forward to create, it is new
            return $this->save($productSpecificationOption);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_SPECIFICATION_OPTION_UPDATE,
            new Events\ProductSpecificationOptionUpdateEvent($productSpecificationOption)
        );
            
        $this->configurationManager->getEntityManager()->persist($productSpecificationOption);
        
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }

    /**
     * delete
     *
     * @param ProductSpecificationOptionInterface $productSpecificationOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Entity will always be persisted.
     */
    public function delete(ProductSpecificationOptionInterface $productSpecificationOption, $flush = true)
    {
        if (!$productSpecificationOption->getId()) {//has never been saved
            return;
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_SPECIFICATION_OPTION_DELETE,
            new Events\ProductSpecificationOptionDeleteEvent($productSpecificationOption)
        );
 
        // find products which use this specification
        $products = $this->configurationManager->getEntityManager()
        ->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
        ->createQueryBuilder()
        ->field('specifications.optionKey')->equals($productSpecificationOption->getKey())
        ->getQuery()
        ->execute();
        
        
        // remove these references
        foreach($products as $product) {
            $attribute = null;
            foreach($product->getSpecifications() as $specification){
                if($productSpecificationOption->getKey() == $specification->getOptionKey()) {
                    $product->removeSpecification($specification);
                }
                    
                // lets update the product
                $this->productManager->update($product, $flush);
            }
        }
        
        $this->configurationManager->getEntityManager()->remove($productSpecificationOption);
        
        if (true === $flush) {
            $this->configurationManager->getEntityManager()->flush();
        }
    }
    
}