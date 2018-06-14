<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Spliced\Component\Commerce\Model\Order;
use Doctrine\ORM\Query\Expr;

/**
 * OrderRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class OrderRepository extends EntityRepository
{

    /**
     * findOneById
     * 
     * @param int $id
     */
    public function findOneById($id)
    {
        
        $query = $this->createQueryBuilder('_order')
          ->select('_order')
          ->where('_order.id = :id')
          ->setParameter('id', $id);
        
        $this->addAllQueries($query);
        
        return $query->getQuery()
          //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
    /**
     * findOneByOrderNumber
     * 
     * @param string $orderNumber
     */
    public function findOneByOrderNumber($orderNumber)
    {
        $query = $this->createQueryBuilder('_order')
          ->select('_order')
          ->where('_order.orderNumber = :orderNumber')
          ->setParameter('orderNumber', $orderNumber);
        
        $this->addAllQueries($query);
        
        return $query->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }

    /**
     * findOneByOrderNumberForCustomer
     *
     * @param string $orderNumber
     * @param CustomerInterface $customer
     */
    public function findOneByOrderNumberForCustomer($orderNumber, CustomerInterface $customer)
    {
        $query = $this->createQueryBuilder('_order')
        ->select('_order')
        ->where('_order.orderNumber = :orderNumber AND customer.id = :customer')
        ->setParameter('orderNumber', $orderNumber)
        ->setParameter('customer', $customer->getId());
         
        $this->addAllQueries($query);
        
        return $query->getQuery()
          //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
    /**
     * findOneByOrderNumberAndEmail
     * 
     * @param string $orderNumber
     * @param string $email
     */
    public function findOneByOrderNumberAndEmail($orderNumber, $email)
    {
        
        $query = $this->createQueryBuilder('_order')
          ->select('_order')
          ->where('_order.orderNumber = :orderNumber AND _order.email = :email')
          ->setParameter('orderNumber', $orderNumber)
          ->setParameter('email', $email);
        
        $this->addAllQueries($query);
         
        return $query->getQuery()
        //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
        ->getSingleResult();
    }
    
    /**
     * findOneByOrderNumberAndEmail
     *
     * @param string $email
     * @param string $billingZipcode
     */
    public function findOneByEmailAndBillingZipcode($email, $billingZipcode)
    {
        $query = $this->createQueryBuilder('_order')
          ->select('_order')
          ->where('_order.billingZipcode = :billingZipcode AND _order.email = :email')
          ->setParameter('billingZipcode', $billingZipcode)
          ->setParameter('email', $email);
         
        $this->addAllQueries($query);
        
        return $query->getQuery()
          //->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
    
    /**
     * findByOrderedSku
     * 
     * @param string $sku
     */
    public function findByOrderedSku($sku)
    {
        $query = $this->createQueryBuilder('_order')
        ->select('_order');
         
        $this->addItemsQuery($query)
        ->addCustomerQuery($query)
        ->addVisitorQuery($query);
         
        $query->where('items.sku = :sku AND _order.orderStatus NOT IN(:orderStatuses)')
        ->setParameter('sku', $sku)
        ->setParameter('orderStatuses', array(Order::STATUS_INCOMPLETE,Order::STATUS_ABANDONED));
         
        return $query->getQuery()
        ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
        ->getResult();
         
    }
    
    /**
     * getRecentOrdersForCustomer
     *
     * @param CustomerInterface $customer
     * @param int $maxOrders - Defaults to 5
     */
    public function getRecentOrdersForCustomer(CustomerInterface $customer, $maxOrders = 5)
    {
        $ids = $this->createQueryBuilder('_order')
          ->select('_order.id')
          ->where('_order.customer = :customer AND _order.orderStatus NOT IN (:statusIncomplete)')
          ->orderBy('_order.createdAt','DESC')
          ->setParameter('customer', $customer)
          ->setParameter('statusIncomplete', array(Order::STATUS_INCOMPLETE,Order::STATUS_ABANDONED))
          ->setMaxResults($maxOrders)
          ->getQuery()      
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getResult(Query::HYDRATE_ARRAY);
        
        $_ids = array();
        foreach($ids as $o){
            $_ids[] = $o['id'];
        }
        
        if(!count($_ids)){
            return array();
        }
        
        $query = $this->createQueryBuilder('_order')
        ->select('_order')
        ->where('_order.id IN(:ids)')
        ->setParameter('ids', $_ids);
        
        $this->addItemsQuery($query);
        
        return $query->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getResult();
    }
    
    /**
     * getOrdersForCustomerListQuery
     * 
     * @param CustomerInterface $customer
     */
    public function getOrdersForCustomerListQuery(CustomerInterface $customer)
    {
        $query = $this->createQueryBuilder('_order')
          ->select('_order')
          ->where('_order.customer = :customer AND _order.orderStatus NOT IN (:statusIncomplete)')
          ->setParameter('customer', $customer->getId())
          ->setParameter('statusIncomplete', array(Order::STATUS_INCOMPLETE,Order::STATUS_ABANDONED));
        
        return $query;
    }
         
    /**
     * getOrdersForCustomerQuery
     * 
     * @param CustomerInterface $customer
     */
    public function getOrdersForCustomerQuery(CustomerInterface $customer)
    {
        $query = $this->createQueryBuilder('_order')
        ->select('_order')
        ->where('_order.customer = :customer AND _order.orderStatus NOT IN (:statusIncomplete)')
        ->setParameter('customer', $customer->getId())
        ->setParameter('statusIncomplete', array(Order::STATUS_INCOMPLETE,Order::STATUS_ABANDONED));
        
        $this->addAllQueries($query);
         
        return $query->getQuery()
        ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
        ->getResult();
        
    }
    

    
    /**
     * addPaymentQuery
     *
     * @param QueryBuilder $query
     * @param string $on
     */
    public function addPaymentQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('payment, paymentMemos, creditCard')
        ->leftJoin($on.'.payment', 'payment')
        ->leftJoin('payment.memos', 'paymentMemos')
        ->leftJoin('payment.creditCard', 'creditCard');
         
        return $this;
    }
    
    /**
     * addShipmentQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    public function addShipmentQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('shipment, shipmentMemos')
        ->leftJoin($on.'.shipment', 'shipment')
        ->leftJoin('shipment.memos', 'shipmentMemos');
         
        return $this;
    }
    
    /**
     * addCustomFieldsQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    public function addCustomFieldsQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('customFields')
        ->leftJoin($on.'.customFields', 'customFields');
    
        return $this;
    }
    
    /**
     * addItemsQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    public function addItemsQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('items, childrenItems')
        ->leftJoin($on.'.items', 'items', Expr\Join::WITH, 'items.parent IS NULL')
        ->leftJoin('items.children', 'childrenItems');
    
        return $this;
    }
    
    /**
     * addCustomerQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    public function addCustomerQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('customer')
        ->leftJoin($on.'.customer', 'customer');
    
        return $this;
    }
    
    /**
     * addVisitorQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    public function addVisitorQuery(QueryBuilder $query, $on = '_order')
    {
        $query->addSelect('visitor')
        ->leftJoin($on.'.visitor', 'visitor');
    
        return $this;
    }
    
    /**
     * addVisitorRequestsQuery
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to visitor
     */
    public function addVisitorRequestsQuery(QueryBuilder $query, $on = 'visitor')
    {
        $query->addSelect('visitorRequests')
        ->leftJoin($on.'.requests', 'visitorRequests');
    
        return $this;
    }
    
    /**
     * addAllQueries
     *
     * @param QueryBuilder $query
     * @param string $on - defaults to _order
     */
    protected function addAllQueries(QueryBuilder $query, $on = '_order')
    {
        $this->addPaymentQuery($query, $on)
        ->addShipmentQuery($query, $on)
        ->addItemsQuery($query, $on)
        ->addCustomFieldsQuery($query, $on)
        ->addVisitorQuery($query, $on)
        ->addVisitorRequestsQuery($query, 'visitor')
        ->addCustomerQuery($query, $on);
        
        return $this;
    }
}
