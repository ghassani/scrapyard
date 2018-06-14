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
use Spliced\Component\Commerce\Event\ProductSpecificationOptionSaveEvent;
use Spliced\Component\Commerce\Event\ProductSpecificationOptionUpdateEvent;
use Spliced\Component\Commerce\Event\ProductSpecificationOptionDeleteEvent;
use Spliced\Component\Commerce\Event\ProductSpecificationOptionValueDeleteEvent;
use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;

/**
 * ProductSpecificationOptionAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSpecificationOptionAdminEventListener
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
     * onProductSpecificationOptionSave
     * 
     * @param ProductSpecificationOptionSaveEvent $event
     */
    public function onProductSpecificationOptionSave(ProductSpecificationOptionSaveEvent $event)
    {
        
    }
    

    /**
     * ProductSpecificationOptionUpdateEvent
     *
     * @param ProductSpecificationOptionUpdateEvent $event
     */
    public function onProductSpecificationOptionUpdate(ProductSpecificationOptionUpdateEvent $event)
    {

    }

    /**
     * onProductSpecificationOptionDelete
     *
     * @param ProductSpecificationOptionDeleteEvent $event
     */
    public function onProductSpecificationOptionDelete(ProductSpecificationOptionDeleteEvent $event)
    {

    }
    
    
        
    /**
     * onProductSpecificationOptionValueDelete
     *
     * @param ProductSpecificationOptionValueDeleteEvent $event
     */
    public function onProductSpecificationOptionValueDelete(ProductSpecificationOptionValueDeleteEvent $event)
    {

    }
}
