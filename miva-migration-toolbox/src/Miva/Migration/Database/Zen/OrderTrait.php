<?php

namespace Miva\Migration\Database\Zen;

trait OrderTrait
{

    /**
     * loadOrderCount
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderCount()
    {
        $query = $this->getConnection()->prepare('SELECT COUNT(DISTINCT orders_id) AS count FROM orders');
        $query->execute(array());
        $results = $query->fetch();
        return isset($results['count']) ? $results['count'] : 0;
    }


    /**
     * getOrders
     * 
     * @param int $limit  Description.
     * @param int $offset Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrders($limit = 1000, $offset = 0)
    {
        $query = $this->getConnection()->prepare(sprintf('
            SELECT 
               o.orders_id AS id,
               o.customers_id AS cust_id,
               o.customers_email_address AS ship_email,
               o.customers_email_address AS bill_email,
               o.delivery_name as ship_name,
               o.delivery_company AS ship_comp,
               o.delivery_street_address AS ship_addr,
               o.delivery_suburb AS ship_addr2,
               o.delivery_city AS ship_city,
               o.delivery_state AS ship_state,
               o.delivery_postcode AS ship_zip,
               o.delivery_country AS ship_cntry,
               o.billing_name as bill_name,
               o.billing_company AS bill_comp,
               o.billing_street_address AS bill_addr,
               o.billing_suburb AS bill_addr2,
               o.billing_city AS bill_city,
               o.billing_state AS bill_state,
               o.billing_postcode AS bill_zip,
               o.billing_country AS bill_cntry,
               o.date_purchased AS order_date,
               o.coupon_code,
               o.cc_type,
               o.cc_number,
               o.cc_expires,
               o.cc_cvv,
               o.orders_status,
               o.order_total,
               o.order_tax
            FROM 
                orders o            
            ORDER BY orders_id ASC
            LIMIT %s OFFSET %s 
        ', $limit, $offset));
        $query->execute(array());
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }


    /**
     * getLastOrderForCustomer
     * 
     * @param mixed $customerId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getLastOrderForCustomer($customerId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
               o.orders_id AS id,
               o.customers_id AS cust_id,
               o.customers_email_address AS ship_email,
               o.customers_email_address AS bill_email,
               o.delivery_name as ship_name,
               o.delivery_company AS ship_comp,
               o.delivery_street_address AS ship_addr,
               o.delivery_suburb AS ship_addr2,
               o.delivery_city AS ship_city,
               o.delivery_state AS ship_state,
               o.delivery_postcode AS ship_zip,
               o.delivery_country AS ship_cntry,
               o.billing_name as bill_name,
               o.billing_company AS bill_comp,
               o.billing_street_address AS bill_addr,
               o.billing_suburb AS bill_addr2,
               o.billing_city AS bill_city,
               o.billing_state AS bill_state,
               o.billing_postcode AS bill_zip,
               o.billing_country AS bill_cntry,
               o.date_purchased AS order_date,
               o.coupon_code,
               o.cc_type,
               o.cc_number,
               o.cc_expires,
               o.cc_cvv,
               o.orders_status,
               o.order_total,
               o.order_tax
            FROM 
                orders o       
            WHERE o.customers_id = ?     
            ORDER BY orders_id DESC
            LIMIT 1');
        $query->execute(array($customerId));

        return $query->fetch();

    }

    /**
     * getOrderItems
     * 
     * @param mixed $orderId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderItems($orderId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                i.orders_id AS order_id,
                i.orders_products_id AS line_id,
                i.products_id AS product_id,
                i.products_model AS code,
                i.products_name AS name,
                i.products_tax AS tax,
                i.products_quantity AS quantity,
                i.products_price AS price
            FROM 
                orders_products i  
            WHERE i.orders_id = ?
        ');
        $query->execute(array($orderId));
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['line_id']] = $r;
        }
        return $return;
    }

    /**
     * loadOrderCharges
     * 
     * @param mixed $orderId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderCharges($orderId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                c.orders_total_id AS charge_id,
                c.orders_id AS order_id,
                c.title AS descrip,
                c.text AS disp_amt,
                c.value AS amount,
                c.class AS type
            FROM 
                orders_total c  
            WHERE c.orders_id = ?
        ');
        $query->execute(array($orderId));
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['charge_id']] = $r;
        }
        return $return;
    }
}