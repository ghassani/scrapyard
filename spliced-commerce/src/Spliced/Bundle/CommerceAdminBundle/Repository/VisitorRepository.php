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

use Spliced\Component\Commerce\Repository\VisitorRepository as BaseVisitorRepository;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

/**
 * VisitorRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class VisitorRepository extends BaseVisitorRepository
{
    /**
     * getAdminListQuery
     */
    public function getAdminListQuery(ListFilter $filter = null, $toQuery = true)
    {
        $query = $this->createQueryBuilder('visitor')
        ->select('visitor, requests, orders')
        ->leftJoin('visitor.requests', 'requests')
        ->leftJoin('visitor.orders', 'orders')
        ->orderBy('visitor.createdAt', "DESC")
        ->andWhere('visitor.sessionId != :bot')
        ->setParameter('bot', 'BOT');
    
        if($filter) {
            $this->applyListFilter($filter, $query);
        }
    
        return $toQuery ? $query->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true) : $query;
    }
    
    /**
     * findOneByIdForView
     * 
     * @param int $id
     */
    public function findOneByIdForView($id)
    {
        $query = $this->createQueryBuilder('visitor')
        ->select('visitor, requests, orders')
        ->leftJoin('visitor.requests', 'requests')
        ->leftJoin('visitor.orders', 'orders')
        ->where('visitor.id = :id')
        ->setParameter('id', $id);

        return $query->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    /**
     * 
     */
    protected function applyListFilter(ListFilter $filter)
    {
        
    }
}
