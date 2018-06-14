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
use Doctrine\ORM\EntityManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Event\ProductAttributeOptionSaveEvent;
use Spliced\Component\Commerce\Event\ProductAttributeOptionUpdateEvent;
use Spliced\Component\Commerce\Event\ProductAttributeOptionDeleteEvent;
use Spliced\Component\Commerce\Event\ProductAttributeOptionValueDeleteEvent;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;

/**
 * ProductAttributeOptionAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionAdminEventListener
{
    /** @var ConfigurationManager */
    protected $configurationManager;


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
     *  getDocumentManager
     *
     *  @return EntityManager
     */
    protected function getDocumentManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
    
    /**
     * onProductAttributeOptionSave
     * 
     * @param ProductAttributeOptionSaveEvent $event
     */
    public function onProductAttributeOptionSave(ProductAttributeOptionSaveEvent $event)
    {

    }
    
    /**
     * ProductAttributeOptionUpdateEvent
     *
     * @param ProductAttributeOptionUpdateEvent $event
     */
    public function onProductAttributeOptionUpdate(ProductAttributeOptionUpdateEvent $event)
    {
        
    }

    /**
     * onProductAttributeOptionDelete
     *
     * @param ProductAttributeOptionDeleteEvent $event
     */
    public function onProductAttributeOptionDelete(ProductAttributeOptionDeleteEvent $event)
    {

    }
            
    /**
     * onProductAttributeOptionValueDelete
     *
     * @param ProductAttributeOptionValueDeleteEvent $event
     */
    public function onProductAttributeOptionValueDelete(ProductAttributeOptionValueDeleteEvent $event)
    {
        $attributeOptionValue = $event->getAttributeOptionValue();
    
        $this->getDocumentManager()->remove($attributeOptionValue);
        $this->getDocumentManager()->flush();
    }
}
