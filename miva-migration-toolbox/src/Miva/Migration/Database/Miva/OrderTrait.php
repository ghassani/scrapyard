<?php

namespace Miva\Migration\Database\Miva;

trait OrderTrait
{
    /**
     * loadOrder
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrder($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Orders')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch(); 
    }

    /**
     * loadOrderItem
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderItem($lineId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderItems')
          ->where('line_id = :line_id')
          ->setParameter('line_id', $lineId)
          ->execute()
          ->fetch(); 
    }

    /**
     * loadOrderCharge
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderCharge($chargeId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderCharges')
          ->where('charge_id = :charge_id')
          ->setParameter('charge_id', $chargeId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getOrderPayment
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderPaymentForOrder($orderId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderPayments')
          ->where('order_id = :order_id')
          ->setParameter('order_id', $orderId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getOrderPayment
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderPayment($paymentId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderPayments')
          ->where('id = :id')
          ->setParameter('id', $paymentId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getOrderShipments
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderShipments($orderId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderShipments')
          ->where('order_id = :order_id')
          ->setParameter('order_id', $orderId)
          ->execute()
          ->fetchAll(); 
    }

    /**
     * getOrderShipment
     * 
     * @param mixed $shipmentId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderShipment($shipmentId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'OrderShipments')
          ->where('id = :id')
          ->setParameter('id', $shipmentId)
          ->execute()
          ->fetch(); 
    }


    /**
     * insertOrder
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertOrder(array $order)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'Orders', 
            $order
        );     
    }


    /**
     * insertOrderCharge
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertOrderCharge(array $orderCharge)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'OrderCharges', 
            $orderCharge
        );       
    }

    /**
     * updateOrderCharge
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateOrderCharge(array $orderCharge)
    {
        if(!isset($orderCharge['charge_id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'OrderCharges', 
            $orderCharge, 
            array(
                'charge_id' => $orderCharge['charge_id'],
            )
        );     
    }



    /**
     * insertOrderShipment
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertOrderShipment(array $orderShipment)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'OrderShipments', 
            $orderShipment
        );     
    }

    /**
     * updateOrderShipment
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateOrderShipment(array $orderShipment)
    {
        if(!isset($orderShipment['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'OrderShipments', 
            $orderShipment, 
            array(
                'id' => $orderShipment['id'],
            )
        );        
    }


    /**
     * insertOrderPayment
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertOrderPayment(array $orderPayment)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'OrderPayments', 
            $orderPayment
        );    
    }

    /**
     * updateOrderPayment
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateOrderPayment(array $orderPayment)
    {
        if(!isset($orderPayment['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'OrderPayments', 
            $orderPayment, 
            array(
                'id' => $orderPayment['id'],
            )
        );     
    }

    /**
     * insertOrderItem
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertOrderItem(array $orderItem)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'OrderItems', 
            $orderItem
        );      
    }

    /**
     * updateOrderItem
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateOrderItem(array $orderItem)
    {
        if(!isset($orderItem['line_id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'OrderItems', 
            $orderItem, 
            array(
                'line_id' => $orderItem['line_id'],
            )
        );    
    }

    /**
     * updateOrder
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateOrder(array $order)
    {
        if(!isset($order['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'Orders', 
            $order, 
            array(
                'id' => $order['id'],
            )
        );  
    }

}