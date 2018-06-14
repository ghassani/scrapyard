<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Order;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Utility\Math\BCMath;

/**
 * OrderHelper
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderHelper
{

    const BCSCALE_DEFAULT = 4;

    const ZERO_VALUE = 00.00;

    /**
     * Contstructor
     *
     * @param ConfigurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getCalculator
     * 
     * @return BCMath 
     */
    public function getCalculator()
    {
        if(!isset($this->calculator)){
            $this->calculator = new BCMath($this->getConfigurationManager()->get('commerce.sales.calculation_precision',static::BCSCALE_DEFAULT));
        }
        return $this->calculator;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getOrderStatus
     */
    public function getOrderStatus(OrderInterface $order)
    {
        return ucwords(str_replace('_',' ', $order->getOrderStatus()));
    }

    /**
     * getOrderTax
     */
    public function getOrderTax(OrderInterface $order)
    {
        $total = static::ZERO_VALUE;

        foreach ($order->getItems() as $item) {
            $total = $this->getCalculator()->add($total, $item->getTaxes());
        }

        return $total;
    }

    /**
     * getOrderTotal
     */
    public function getOrderSubTotal(OrderInterface $order, $includeShipping = true, $searchChildren = true)
    {
        $total = static::ZERO_VALUE;
        if ($order->getShipment() && $includeShipping) { // add shipping
            $total = $this->getCalculator()->add($total, $order->getShipment()->getShipmentPaid());
        }
        
        $searchItems = function($items, $searchChildren = true) use(&$searchItems) {
            $return = static::ZERO_VALUE;
            foreach($items as $item) {
                $return = $this->getCalculator()->add($return, $this->getCalculator()->multiply($item->getSalePrice(), $item->getQuantity()));
                if(true === $searchChildren && $item->hasChildren()) {
                    $return = $this->getCalculator()->add($return, $searchItems($item->getChildren(), $searchChildren));
                }
            }
            return $return;
        };
        
        return $this->getCalculator()->add($total, $searchItems($order->getItems(), $searchChildren));
    }

    /**
     * getOrderTotal
     */
    public function getOrderTotal(OrderInterface $order, $includeShipping = true, $includeTaxes = true, $searchChildren = true)
    {
        $total = static::ZERO_VALUE;

        if ($order->getShipment() && $includeShipping) { // add shipping
            $total = $this->getCalculator()->add($total, $order->getShipment()->getShipmentPaid());
        }
        
        $searchItems = function($items, $searchChildren = true) use(&$searchItems) {
            $return = static::ZERO_VALUE;
            foreach($items as $item) {
                $return = $this->getCalculator()->add($return, $item->getFinalPrice());
                if(true === $searchChildren && $item->hasChildren()) {
                    $return = $this->getCalculator()->add($return, $searchItems($item->getChildren(), $searchChildren));
                }
            }
            return $return;
        };
        
        return $this->getCalculator()->add($total, $searchItems($order->getItems(), $searchChildren));

    }

    /**
     * getOrderShipping
     */
    public function getOrderShipping(OrderInterface $order)
    {
        $total = static::ZERO_VALUE;
        if ($order->getShipment()) {
            $total = $this->getCalculator()->add($total, $order->getShipment()->getShipmentPaid());
        }

        return $total;
    }
    
    /**
     * getAvailableStatuses
     * 
     * @return array - An array of available statuses
     */
    public function getAvailableStatuses()
    {
        $classData = new \ReflectionClass('Spliced\Component\Commerce\Model\OrderInterface');
        $return = array();
        foreach($classData->getConstants() as $name => $value) {
            if(preg_match('/^STATUS_/', $name)){
                $return[] = $value;
            }
        }
        return $return;
    }
    
    /**
     * getOrderTotalItems
     */
    public function getOrderTotalItems(OrderInterface $order)
    {
        $total = static::ZERO_VALUE;
    
        foreach ($order->getItems() as $item) {
            $total = $this->getCalculator()->add($total, $item->getQuantity());
        }
    
        return $total;
    }
}
