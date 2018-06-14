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
use Doctrine\ORM\Query;

/**
 * VisitorRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class VisitorRepository extends EntityRepository
{

    /**
     * findOneBySessionId
     * 
     * @param string $sessionId
     */
    public function findOneBySessionId($sessionId)
    {
        return $this->createQueryBuilder('visitor')
          ->select('visitor')
          ->where('visitor.sessionId = :sessionId')
          ->setParameter('sessionId', $sessionId)
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
        /**
     * findOneBySessionIdWithRequestCount
     * 
     * @param string $sessionId
     */
    public function findOneBySessionIdWithRequestCount($sessionId)
    {
        return $this->createQueryBuilder('visitor')
          ->select('visitor, count(requests) as requestCount')
          ->where('visitor.sessionId = :sessionId')
          ->leftJoin('visitor.requests', 'requests')
          ->setParameter('sessionId', $sessionId)
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
    /**
     * findOneById
     * 
     * @param int $id
     */
    public function findOneById($id)
    {
        return $this->createQueryBuilder('visitor')
          ->select('visitor')
          ->where('visitor.id = :id')
          ->setParameter('id', $id)
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
    /**
     * findOneByIdWithRequestCount
     * 
     * @param int $id
     */
    public function findOneByIdWithRequestCount($id)
    {
        $date = new \DateTime('now');
        
        return $this->createQueryBuilder('visitor')
          ->select('visitor, count(requests) as requestCount')
          ->where('visitor.id = :id')
          //->leftJoin('visitor.requests', 'requests')
          ->leftJoin('visitor.requests', 'requests', \Doctrine\ORM\Query\Expr\Join::WITH, 'requests.createdAt BETWEEN :date1 AND :date2')
          ->setParameter('id', $id)
          ->setParameters(array(
              'id' => $id,
              'date1' => $date->format('Y-m-d 00:00:01'),
              'date2' => $date->format('Y-m-d 23:59:59'),
          ))
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }
    
}
