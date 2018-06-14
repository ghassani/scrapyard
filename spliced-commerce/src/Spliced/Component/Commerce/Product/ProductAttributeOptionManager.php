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
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Event as Events;

/**
 * ProductAttributeOptionManager
 * 
 * Handles the saving and updating of ProductAttributeOption's
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionManager
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
     * getRepository
     * 
     * Get's the ProductAttributeOption Repository
     * 
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->configurationManager->getDocumentManager()
          ->getRepository(
              $this->configurationManager
                  ->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION
        ));
    }
    
    /**
     * create
     * 
     * Creates a new ProductAttributeOption for manipulation. You will need to 
     * save the changes made after creation. You can use the saveProductAttributeOption 
     * method to do this.
     * 
     * @return ProductAttributeOptionInterface
     */
    public function create()
    {
        return $this->configurationManager->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION);
    }
    
    /**
     * save
     * 
     * Saves and persists a new product. If an existing product is passed, 
     * this method will just forward to updateProductAttributeOption method.
     * 
     * @param ProductAttributeOptionInterface $productAttributeOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Document will always be persisted.
     */
    public function save(ProductAttributeOptionInterface $productAttributeOption, $flush = true)
    {
        if ($productAttributeOption->getId()) { // forward to update, it is not new
            return $this->update($productAttributeOption);
        }
        
        $productAttributeOption->setKey(strtolower(preg_replace(array('/[^A-Z0-9\s_\-]/i','/\s/'), array('','_'), $productAttributeOption->getKey())));
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_ATTRIBUTE_OPTION_SAVE,
            new Events\ProductAttributeOptionSaveEvent($productAttributeOption)
        );
        
        $this->configurationManager->getDocumentManager()->persist($productAttributeOption);
                
        if (true === $flush) {
            $this->configurationManager->getDocumentManager()->flush();
        }
    }
    

    /**
     * update
     * 
     * Updates an existing product. If a new product is passed, 
     * this method will just forward to saveProductAttributeOption method.
     * 
     * @param ProductAttributeOptionInterface $productAttributeOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Document will always be persisted.
     */
    public function update(ProductAttributeOptionInterface $productAttributeOption, $flush = true)
    {
        if (!$productAttributeOption->getId()) { // forward to create, it is new
            return $this->save($productAttributeOption);
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_ATTRIBUTE_OPTION_UPDATE,
            new Events\ProductAttributeOptionUpdateEvent($productAttributeOption)
        );

        $this->updateEmbedMany($productAttributeOption);
        foreach($productAttributeOption->getValues() as $value) {
            $this->updateEmbedMany($value);
        }
        
        $this->configurationManager->getDocumentManager()->persist($productAttributeOption);
                
        if (true === $flush) {
            $this->configurationManager->getDocumentManager()->flush();
        }
    }

    /**
     * deleteProductAttributeOption
     *
     * @param ProductAttributeOptionInterface $productAttributeOption
     * @param bool $flush - Flushes and updates the database as well. 
     *                      Document will always be persisted.
     */
    public function delete(ProductAttributeOptionInterface $productAttributeOption, $flush = true)
    {
        if (!$productAttributeOption->getId()) {//has never been saved
            return;
        }
        
        // notify the event dispatcher to handle any user hooks
        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_PRODUCT_ATTRIBUTE_OPTION_DELETE,
            new Events\ProductAttributeOptionDeleteEvent($productAttributeOption)
        );
        
        // find products which use this attribute
        $products = $this->configurationManager->getDocumentManager()
          ->getRepository($this->configurationManager->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
          ->createQueryBuilder()
          ->field('attributes.optionKey')->equals($productAttributeOption->getKey())
          ->getQuery() 
          ->execute();
        
        // remove these references
        foreach($products as $product) {
            $attribute = null;
            foreach($product->getAttributes() as $attribute){
                if($productAttributeOption->getKey() == $attribute->getOptionKey()) {
                    $product->removeAttribute($attribute);
                }
            
                // notify the event dispatcher to handle any user hooks
                $this->productManager->update($product, $flush);
            }
        }
        
        $this->configurationManager->getDocumentManager()->remove($productAttributeOption);
                
        if (true === $flush) {
            $this->configurationManager->getDocumentManager()->flush();
        }
    }
    
    /**
     *  updateEmbedMany
     * 
     *  as of 1/30/2014 with Doctrine, embedded documents duplicate items
     *  like the issue here: 
     *  http://stackoverflow.com/questions/16267336/doctrine-mongo-odm-duplicating-embedded-documents-in-symfony
     *  cloning the collection objects seems to work // would like to find a better solution of 
     *  find what is causing it  so we just iterate over every EmbedMany and clone the collection
     */
    protected function updateEmbedMany($object)
    {
        $objectMetaData = $this->configurationManager->getDocumentManager()->getClassMetadata(get_class($object));
        foreach($objectMetaData->getFieldNames() as $fieldName) {
            if($objectMetaData->hasEmbed($fieldName) && !$objectMetaData->isSingleValuedEmbed($fieldName)){
                $objectMetaData->setFieldValue($object, $fieldName, clone $objectMetaData->getFieldValue($object, $fieldName));
            }
        }
    }
}