<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Repository;

use Spliced\Component\Commerce\Repository\OrderRepository as BaseOrderRepository;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Component\Commerce\Model\OrderInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;


/**
 * OrderRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderRepository extends BaseOrderRepository
{
    
    public function getTodaysOrders()
    {
        
        $query = $this->createQueryBuilder('_order')
        ->select('_order, items')
        ->leftJoin('_order.items','items')
        ->where('_order.orderStatus NOT IN (:orderStatuses) AND _order.completedAt > :dayStart')
        ->setParameter('orderStatuses', array(OrderInterface::STATUS_INCOMPLETE,OrderInterface::STATUS_ABANDONED))
        ->setParameter('dayStart', date('Y-m-d'))
        //->setParameter('dayEnd', date('Y-m-d 59:59:59'))
        ->orderBy('_order.completedAt','DESC');
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * getOrdersForDaysQuery
     * 
     * @param int $days - Number of Days to Go Back From Today. Defaults to 7 Days
     * 
     * @return QueryBuilder
     */
    public function getOrdersForDaysQuery($days = null, $includeIncomplete = false)
    {
        if(is_null($days)||!is_int($days)){
            $days = 7;
        }
    
        $currentDate = new \DateTime();
        $previousDate = new \DateTime('now - '.$days.' days');
    
        $query = $this->createQueryBuilder('_order')
        ->select('_order, items')
        ->leftJoin('_order.items','items')
        ->where('_order.completedAt BETWEEN :dayStart AND :dayEnd')
        ->setParameter('dayStart', $previousDate->format('Y-m-d'))
        ->setParameter('dayEnd', $currentDate->format('Y-m-d'))
        ->orderBy('_order.completedAt','DESC');
        
        if(!$includeIncomplete){
            $query->andWhere('_order.orderStatus NOT IN (:orderStatuses) ')
             ->setParameter('orderStatuses', array(OrderInterface::STATUS_INCOMPLETE,OrderInterface::STATUS_ABANDONED));
        }
        
        return $query;
    }
    
    /**
     * getOrdersForDays 
     * 
     * @param int $days - Number of Days to Go Back From Today. Defaults to 7 Days
     * 
     * @return array
     */
    public function getOrdersForDays($days = null, $includeIncomplete = false)
    {
        $query = $this->getOrdersForDaysQuery($days, $includeIncomplete);

        return $query->getQuery()
        ->getResult();
    }
    
    /**
     * getOrdersForDaysArray
     *
     * @param int $days - Number of Days to Go Back From Today. Defaults to 7 Days
     *
     * @return array
     */
    public function getOrdersForDaysForChart($days = null)
    {
        $query = $this->getOrdersForDaysQuery($days, true);
        
        $result = $query->getQuery()
        ->getResult();
        
        $return = array();
        
        $startDate = new \DateTime('-'.$days.' days');
        $targetDate = new \DateTime('now +1 day');
        while($startDate->format('Y-m-d') != $targetDate->format('Y-m-d')){
            $return[$startDate->format('Y-m-d')] = array(
                'count' => 0,
                'incomplete' => 0,
                'abandoned' => 0,
                'items' => 0,
                'orders' => array(),
            );
            $startDate->modify('+1 day');
        } 
        
        foreach($result as $order){
            $orderDate = $order->getCompletedAt()->format('Y-m-d');
            
            if(!isset($return[$orderDate])){
                $return[$orderDate] = array(    
                    'count' => 0,
                    'incomplete' => 0,
                    'abandoned' => 0,
                    'items' => 0,
                    'orders' => array(),
                );
            }
            
            
            if($order->getOrderStatus() == OrderInterface::STATUS_INCOMPLETE){
                $return[$orderDate]['incomplete'] += 1;
            } else if($order->getOrderStatus() == OrderInterface::STATUS_ABANDONED){
                $return[$orderDate]['abandoned'] += 1;
            } else {
                foreach($order->getItems() as $item){
                    $return[$orderDate]['items'] += $item->getQuantity();
                }
                $return[$orderDate]['count'] += 1;
                $return[$orderDate]['orders'][] = $order;
            }    
        }
        return $return;
    }
    
    /**
     * getAdminListQuery
     */
    public function getAdminListQuery(ListFilter $filter = null, $toQuery = true)
    {
        $query = $this->createQueryBuilder('_order')
        ->select('_order, items')
        ->leftJoin('_order.items','items')
        ->where('_order.orderStatus NOT IN (:orderStatuses)')
        ->setParameter('orderStatuses', array(OrderInterface::STATUS_INCOMPLETE,OrderInterface::STATUS_ABANDONED))
        ->orderBy('_order.completedAt','DESC');
        
        if($filter) {
            $this->applyListFilter($filter, $query);
        }
        
        return $toQuery ? $query->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true) : $query;
        
    }
    
    /**
     * 
     */
    public function getAdminIncompleteListQuery(ListFilter $filter = null, $toQuery = true)
    {
        $query = $this->createQueryBuilder('_order')
        ->select('_order, items')
        ->leftJoin('_order.items','items')
        ->where('_order.orderStatus IN (:orderStatuses)')
        ->setParameter('orderStatuses', array(OrderInterface::STATUS_INCOMPLETE,OrderInterface::STATUS_ABANDONED))
        ->orderBy('_order.createdAt','DESC');
        

        if($filter) {
            $this->applyListFilter($filter, $query);
        }
        
        return $toQuery ? $query->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true) : $query;
    }
    
    /**
     * findOneById
     * 
     * @param int $id
     */
    public function findOneById($id)
    {
        return $this->createQueryBuilder('_order')
        ->select('_order, items, payment, creditCard, paymentMemos, 
                shipment, shipmentMemos, customFields, customField,
                visitor, visitorRequests, memos')
        ->leftJoin('_order.items','items')
        ->leftJoin('_order.payment','payment')
        ->leftJoin('payment.creditCard','creditCard')
        ->leftJoin('payment.memos','paymentMemos')
        ->leftJoin('_order.shipment','shipment')
        ->leftJoin('shipment.memos','shipmentMemos')
        ->leftJoin('_order.customFields','customFields')
        ->leftJoin('customFields.field','customField')
        ->leftJoin('_order.visitor','visitor')
        ->leftJoin('visitor.requests','visitorRequests')
        ->leftJoin('_order.memos','memos')
        ->where('_order.id = :order')
        ->setParameter('order', $id)
        ->getQuery()
        ->getSingleResult();
    }
    
    /**
     * findOneByIdForWebService
     *
     * @param int $orderId
     */
    public function findOneByIdForWebService($orderId)
    {
        return $this->getWebServiceQuery()
        ->where('_order.id = :order')
        ->setParameter('order', $orderId)
        ->getQuery()
        ->getSingleResult(Query::HYDRATE_ARRAY);
    }

    /**
     * findOneByShipmentId
     * 
     * @param int $shipmentId
     * 
     * @return QueryBuilder
     */
    public function findOneByShipmentId($shipmentId)
    {
        return $this->createQueryBuilder('_order')
            ->select('_order, items, payment, creditCard, paymentMemos,
            shipment, shipmentMemos, customFields, customField')
            ->leftJoin('_order.items','items')
            ->leftJoin('_order.payment','payment')
            ->leftJoin('payment.creditCard','creditCard')
            ->leftJoin('payment.memos','paymentMemos')
            ->leftJoin('_order.shipment','shipment')
            ->leftJoin('shipment.memos','shipmentMemos')
            ->leftJoin('_order.customFields','customFields')
            ->leftJoin('customFields.field','customField')
            ->where('shipment.id = :shipment')
            ->setParameter('shipment', $shipmentId)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * findOneByPaymentId
     *
     * @param int $paymentId
     *
     * @return QueryBuilder
     */
    public function findOneByPaymentId($paymentId)
    {
        return $this->createQueryBuilder('_order')
        ->select('_order, items, payment, creditCard, paymentMemos,
            shipment, shipmentMemos, customFields, customField')
                ->leftJoin('_order.items','items')
                ->leftJoin('_order.payment','payment')
                ->leftJoin('payment.creditCard','creditCard')
                ->leftJoin('payment.memos','paymentMemos')
                ->leftJoin('_order.shipment','shipment')
                ->leftJoin('shipment.memos','shipmentMemos')
                ->leftJoin('_order.customFields','customFields')
                ->leftJoin('customFields.field','customField')
                ->where('payment.id = :payment')
                ->setParameter('payment', $paymentId)
                ->getQuery()
                ->getSingleResult();
    }
    /**
     * getWebServiceQuery
     *
     * @return QueryBuilder
     */
    public function getWebServiceQuery()
    {
        return $this->createQueryBuilder('_order')
        ->select('_order, items, payment, creditCard, paymentMemos, 
                shipment, shipmentMemos, customFields, customField,
                visitor, visitorRequests')
        ->leftJoin('_order.items','items')
        ->leftJoin('_order.payment','payment')
        ->leftJoin('payment.creditCard','creditCard')
        ->leftJoin('payment.memos','paymentMemos')
        ->leftJoin('_order.shipment','shipment')
        ->leftJoin('shipment.memos','shipmentMemos')
        ->leftJoin('_order.customFields','customFields')
        ->leftJoin('customFields.field','customField')
        ->leftJoin('_order.visitor','visitor')
        ->leftJoin('visitor.requests','visitorRequests');
    }
    
    /**
     * applyListFilter
     * 
     * @param ListFilter $filter
     * @param QueryBuilder $query
     */
    protected function applyListFilter(ListFilter $filter, QueryBuilder $query)
    {
        if(isset($filter['id']) && $filter['id']){
            $ids = explode(',', $filter['id']);
            $query->andWhere('_order.id IN(:orderIds)')
              ->setParameter('orderIds', array_values($ids));
        }
        
        if(isset($filter['orderNumber']) && $filter['orderNumber']){
             
            if(isset($filter['orderNumberOptions']) && $filter['orderNumberOptions'] == 1){
                //regexp
                $query->andWhere('REGEXP(_order.orderNumber,:orderNumberExp) = 1')
                ->setParameter('orderNumberExp', $filter['orderNumber']);
            
            } else if(isset($filter['orderNumberOptions']) && $filter['orderNumberOptions'] == 2){
                // not containing
                $query->andWhere('_order.orderNumber NOT LIKE :orderNumber')
                ->setParameter('orderNumber', '%'.$filter['orderNumber'].'%');
                 
            } else if(isset($filter['orderNumberOptions']) && $filter['orderNumberOptions'] == 3){
                // from begining
                $query->andWhere('_order.orderNumber LIKE :orderNumber')
                ->setParameter('orderNumber', $filter['orderNumber'].'%');
            } else if(isset($filter['orderNumberOptions']) && $filter['orderNumberOptions'] == 4){
                // from end
                $query->andWhere('_order.orderNumber LIKE :orderNumber')
                ->setParameter('orderNumber', '%'.$filter['orderNumber']);
            } else {
                $query->andWhere('_order.orderNumber LIKE :orderNumber')
                ->setParameter('orderNumber', '%'.$filter['orderNumber'].'%');
            }
        }
        
        if(isset($filter['orderStatus']) && $filter['orderStatus']){
            if(is_array($filter['orderStatus'])){
                $query->andWhere('_order.orderStatus IN (:orderStatusesFilter)')
                ->setParameter('orderStatusesFilter', array_values($filter['orderStatus']));
            } else {
                $query->andWhere('_order.orderStatus = :orderStatusFilter')
                ->setParameter('orderStatusFilter', $filter['orderStatus']);
            }
        }
        /*
        if((isset($filter['completedAtFrom']) && $filter['completedAtFrom']) || (isset($filter['completedAtTo']) && $filter['completedAtTo'])){
            $completedAtFrom = isset($filter['completedAtFrom']) && $filter['completedAtFrom'] ? new \DateTime($filter['completedAtFrom']) : null;
            $completedAtTo = isset($filter['completedAtTo']) && $filter['completedAtTo'] ? new \DateTime($filter['completedAtTo']) : null;
            
            if($completedAtTo && $completedAtFrom){
                
            } else if($completedAtFrom && !$completedAtTo){
                
            } else if(!$completedAtFrom && $completedAtTo){
                
            }
            
            if(is_array($filter['orderStatus'])){
                $query->andWhere('_order.orderStatus IN :orderStatuses')
                ->setParameter('orderStatuses', array_values($filter['orderStatus']));
            } else {
                $query->andWhere('_order.orderStatus = :orderStatus')
                ->setParameter('orderStatus', $filter['orderStatus']);
            }
        }*/
    }
}
