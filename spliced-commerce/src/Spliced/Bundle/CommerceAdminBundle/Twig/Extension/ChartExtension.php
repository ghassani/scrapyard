<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Twig\Extension;

use Spliced\Component\Commerce\Model\OrderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;

/**
 * ChartExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ChartExtension extends \Twig_Extension
{
    
    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_admin_order_chart_data_by_date' => new \Twig_Function_Method($this, 'orderChartDataByDate'),
            'commerce_admin_visitor_chart_data_by_date' => new \Twig_Function_Method($this, 'visitorChartDataByDate'),
            'commerce_admin_visitor_browser_chart_data_by_date_range' => new \Twig_Function_Method($this, 'visitorBrowserChartDataByDateRange'),
        );
    }
    
    public function visitorBrowserChartDataByDateRange(\DateTime $from = null, \DateTime $to = null)
    {
        if(is_null($from)){
            $from = new \DateTime(sprintf('first day of %s %s', date('F'), date('Y')));
        }
        
        if(is_null($to)){
            $to = new \DateTime(sprintf('last day of %s %s', date('F'), date('Y')));
        }
        
        $baseCountQuery = $this->getEntityManager()->createQueryBuilder()
        ->select('count(visitor.userAgent), visitor.userAgent')
        ->from('SplicedCommerceAdminBundle:Visitor', 'visitor')
        ->where('visitor.createdAt BETWEEN :dayStart AND :dayEnd AND visitor.isBot != 1')
        ->groupBy('request.createdAt')
        ->setParameter('dayStart', $from->format('Y-m-d'))
        ->setParameter('dayEnd', $to->format('Y-m-d'))
        ->groupBy('visitor.userAgent');
        
        $count = $baseCountQuery->getQuery()->getResult(Query::HYDRATE_ARRAY);
        
        $return = array('data' => array());
        
        foreach($count as $row){
            $browserData = UserAgentHelper::parseUserAgent($row['userAgent']);
            
            if(empty($browserData['browser'])){
                $browserData['browser'] = 'Other';
            }
            
            $hasRecord = false;
            //$label = $browserData['browser'].' '.$browserData['version'];
            $label = $browserData['browser'];
            
            foreach($return['data'] as &$dataSet){
                if(isset($dataSet[0]) && $dataSet[0] == $label){
                    $dataSet[1] += $row[1];
                    $hasRecord = true;
                    break;
                }
            }
            
            if(!$hasRecord){
                $return['data'][] = array($label, $row[1]);
            }
        }
        return json_encode($return);
    }

    /**
     * visitorChartDataByDate
     */
    public function visitorChartDataByDate($days = 1)
    {
        $currentDate = new \DateTime();
        $previousDate = new \DateTime('now - '.$days.' days');
        
        $baseCountQuery = $this->getEntityManager()->createQueryBuilder()
        ->select('count(request), visitor.initialReferer, request.createdAt')
        ->from('SplicedCommerceAdminBundle:VisitorRequest', 'request')
        ->leftJoin('request.visitor', 'visitor')
        ->where('request.createdAt BETWEEN :dayStart AND :dayEnd AND visitor.isBot != 1')
        ->groupBy('request.createdAt')
        ->setParameter('dayStart', $previousDate->format('Y-m-d'))
        ->setParameter('dayEnd', $currentDate->format('Y-m-d'))
        ->groupBy('visitor.initialReferer');
        
        $count = $baseCountQuery->getQuery()->getResult(Query::HYDRATE_ARRAY);

        $return = array();
        foreach($count as $c){
            $initialReferer = empty($c['initialReferer']) ? 'Other' : $c['initialReferer'];
            if(!isset($return[$initialReferer])){
                $return[$initialReferer] = array('label' => $initialReferer, 'data' => array());
            }
            $hasTimeAlready = false;
            $timestamp = strtotime(sprintf("%s UTC", $c['createdAt']->format('Y-m-d'))) * 1000;
            foreach($return[$initialReferer]['data'] as &$data){
                if(isset($data[0]) && $data[0] == $timestamp){
                    $hasTimeAlready = true;
                    $data[1] += $c[1];
                }
            }
            
            if(!$hasTimeAlready){
                $return[$initialReferer]['data'][] = array($timestamp, $c[1]);
            }
            
        }
        
        return json_encode($return);
    }
    
    /**
     * orderChartDataByDate
     */
    public function orderChartDataByDate($days = 7)
    {
        


        $currentDate = new \DateTime();
        $previousDate = new \DateTime('now - '.$days.' days');
        
        //$return = $this->createBaseDateArray($days);
        
        $return = array(
            'orders' => array(
                'label' => 'Orders',
                'data' => $this->createBaseDateArray($days),
            ),
            'items' => array(
                'label' => 'Items',
                'data' => $this->createBaseDateArray($days),
            ),
            'incomplete' => array(
                'label' => 'Incomplete Orders',
                'data' => $this->createBaseDateArray($days),
            ),
            'cancelled' => array(
                'label' => 'Cancelled Orders',
                'data' => $this->createBaseDateArray($days),
            ),
        );
        
        
        $baseCountQuery = $this->getEntityManager()->createQueryBuilder()
          ->from('SplicedCommerceAdminBundle:Order', '_order')
          ->where('_order.completedAt BETWEEN :dayStart AND :dayEnd')
          ->groupBy('_order.completedAt')
          ->setParameter('dayStart', $previousDate->format('Y-m-d'))
          ->setParameter('dayEnd', $currentDate->format('Y-m-d'));
        
        $completedOrdersQuery = clone $baseCountQuery;
        $completedOrdersQuery->select('_order.completedAt, count(_order) as orderCount, SUM(items.quantity) as itemCount')
         ->leftJoin('_order.items', 'items')
         ->andWhere('_order.orderStatus NOT IN(:incompleteStatuses)')
          ->setParameter('incompleteStatuses', array(
                  OrderInterface::STATUS_ABANDONED,
                  OrderInterface::STATUS_INCOMPLETE,
                  OrderInterface::STATUS_CANCELLED,
          ));
          
          $completedOrders = $completedOrdersQuery->getQuery()
          ->getResult(Query::HYDRATE_ARRAY); 

          $return = $this->processOrderCountQueryResut($return, 'orders', $completedOrders);
          $return = $this->processOrderCountQueryResut($return, 'items', $completedOrders);
                  
          
          $incompleteOrdersQuery = clone $baseCountQuery;
          $incompleteOrdersQuery->select('_order.completedAt, count(_order) as orderCount')
          ->andWhere('_order.orderStatus IN(:incompleteStatuses)')
          ->setParameter('incompleteStatuses', array(
                  OrderInterface::STATUS_ABANDONED,
                  OrderInterface::STATUS_INCOMPLETE,
          ));
          
          $incompleteOrders = $incompleteOrdersQuery->getQuery()
          ->getResult(Query::HYDRATE_ARRAY);
          
          $return = $this->processOrderCountQueryResut($return, 'incomplete', $incompleteOrders);
          

          $cancelledOrdersQuery = clone $baseCountQuery;
          $cancelledOrdersQuery->select('_order.completedAt, count(_order) as orderCount')
          ->andWhere('_order.orderStatus IN(:cancelledStatus)')
          ->setParameter('cancelledStatus', array(
                  OrderInterface::STATUS_CANCELLED,
          ));
          
          $cancelledOrders = $cancelledOrdersQuery->getQuery()
          ->getResult(Query::HYDRATE_ARRAY);
          
          $return = $this->processOrderCountQueryResut($return, 'cancelled', $cancelledOrders);
          
        return json_encode($return);
        
        
        /*
        foreach($orders as $order){
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
                //$return[$orderDate]['orders'][] = $order;
            }
        }
        return json_encode($return);*/
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_admin_chart';
    }

    /**
     * processOrderCountQueryResut
     */
    protected function processOrderCountQueryResut($buffer, $targetSet, $results)
    {
        $return = $buffer;
        
        if(!isset($return[$targetSet])){
            $return[$targetSet] = array(
                'label' => '',
                'data' => array(),
            );
        }
        
        foreach($results as $order) {
            $timestamp = strtotime(sprintf("%s UTC", $order['completedAt']->format('Y-m-d'))) * 1000;
            $foundDateInSet = false;
            
            foreach($return[$targetSet]['data'] as &$dateRow){
                if($dateRow[0] == $timestamp){
                    $foundDateInSet = true;
                    if($targetSet == 'items'){
                        $dateRow[1] += $order['itemCount'];
                    } else {
                        $dateRow[1] += 1;
                    }
                    
                }
            }
            
            if(!$foundDateInSet){
                if($targetSet == 'items'){
                    $return[$targetSet]['data'][] = array($timestamp, $order['itemCount']);
                } else {
                    $return[$targetSet]['data'][] = array($timestamp, 1);
                }
                
            }
            /*
            if(isset($return['cancelled']['data'][$order['completedAt']->format('Y-m-d')])){
                
            } else {
                $return['cancelled']['data'][$order['completedAt']->format('Y-m-d')] = array($timestamp, 1);
            }*/
        
        }
        
        return $return;
    }
    

    
    /**
     * 
     */
    protected function createBaseDateArray($days)
    {
        $startDate = new \DateTime('-'.$days.' days');
        $targetDate = new \DateTime('now +1 day');
        
        $return = array();
        while($startDate->format('Y-m-d') != $targetDate->format('Y-m-d')){
            $timestamp = strtotime(sprintf("%s UTC", $startDate->format('Y-m-d'))) * 1000;
            
            $return[] = array($timestamp, 0);
            
            $startDate->modify('+1 day');
        }
        
        return $return;
    }
}
