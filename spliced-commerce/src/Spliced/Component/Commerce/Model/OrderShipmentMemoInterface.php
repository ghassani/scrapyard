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
 * OrderShipmentMemoInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderShipmentMemoInterface
{
    /**
     * Set createdBy
     *
     * @param  string            $createdBy
     * @return OrderShipmentMemo
     */
    public function setCreatedBy($createdBy);
    
    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy();
    
    /**
     * setTrackingNumber
     *
     * @param  string            $trackingNumber
     * @return OrderShipmentMemo
     */
    public function setTrackingNumber($trackingNumber);
    
    /**
     * Get trackingNumber
     *
     * @return string
     */
    public function getTrackingNumber();
    
    /**
     * Set memo
     *
     * @param  string            $memo
     * @return OrderShipmentMemo
     */
    public function setMemo($memo);
    
    /**
     * Get memo
     *
     * @return string
     */
    public function getMemo();
    
    /**
     * Set memoData
     *
     * @param  array             $memoData
     * @return OrderShipmentMemo
     */
    public function setMemoData($memoData);
    
    /**
     * Get memoData
     *
     * @return array
     */
    public function getMemoData();
    
    /**
     * Set previousStatus
     *
     * @param  string            $previousStatus
     * @return OrderShipmentMemo
     */
    public function setPreviousStatus($previousStatus);
    
    /**
     * Get previousStatus
     *
     * @return string
     */
    public function getPreviousStatus();
    
    /**
     * Set changedStatus
     *
     * @param  string            $changedStatus
     * @return OrderShipmentMemo
     */
    public function setChangedStatus($changedStatus);
    
    /**
     * Get changedStatus
     *
     * @return string
     */
    public function getChangedStatus();
    
    /**
     * Get shipment
     *
     * @return OrderShipmentInterface
     */
    public function getShipment();
    
    /**
     * Set shipment
     *
     * @param OrderShipmentInterface
     */
    public function setShipment(OrderShipmentInterface $shipment);
    
    /**
     * getCreatedAt
     *
     * @return DateTime $createdAt
     */
    public function getCreatedAt();
    
    /**
     * setCreatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setCreatedAt(\DateTime $createdAt);
    /**
     * getUpdatedAt
     *
     * @return DateTime $updatedAt
     */
    public function getUpdatedAt();
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt);
}
