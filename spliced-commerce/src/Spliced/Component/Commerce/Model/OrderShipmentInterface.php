<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * OrderShipmentInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderShipmentInterface
{
    /**
     * Set shipmentProvider
     *
     * @param  string        $shipmentProvider
     * @return OrderShipment
     */
    public function setShipmentProvider($shipmentProvider);
    
    /**
     * Get shipmentProvider
     *
     * @return string
     */
    public function getShipmentProvider();
    
    /**
     * Set shipmentMethod
     *
     * @param  string        $shipmentMethod
     * @return OrderShipment
     */
    public function setShipmentMethod($shipmentMethod);
    
    /**
     * Get shipmentMethod
     *
     * @return string
     */
    public function getShipmentMethod();
    
    /**
     * Set shipmentCost
     *
     * @param  float         $shipmentCost
     * @return OrderShipment
     */
    public function setShipmentCost($shipmentCost);
    
    /**
     * Get shipmentCost
     *
     * @return float
     */
    public function getShipmentCost();
    
    /**
     * Set shipmentPaid
     *
     * @param  float         $shipmentPaid
     * @return OrderShipment
     */
    public function setShipmentPaid($shipmentPaid);
    
    /**
     * Get shipmentPaid
     *
     * @return float
     */
    public function getShipmentPaid();
    
    /**
     * Set isInsured
     *
     * @param  string        $isInsured
     * @return OrderShipment
     */
    public function setIsInsured($isInsured);
    
    /**
     * Get isInsured
     *
     * @return string
     */
    public function getIsInsured();
    

    /**
     * Get Order
     *
     * @return OrderInterface
     */
    public function getOrder();
    
    /**
     * Set Order
     *
     * @param OrderInterface
     */
    public function setOrder(OrderInterface $order);
    
    /**
     * Set shipmentStatus
     *
     * @return sting
     */
    public function getShipmentStatus();
    
    /**
     * Set shipmentStatus
     *
     * @param sting
     */
    public function setShipmentStatus($shipmentStatus);

}
