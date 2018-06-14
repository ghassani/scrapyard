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
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;

/**
 * RouteRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class RouteRepository extends EntityRepository implements RouteRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function matchRoute($requestPath)
    {
        return $this->createQueryBuilder('route')
          ->select('route')
          ->where('route.requestPath = :path')
          ->setParameter('path', $requestPath)
          ->getQuery()
          ->getSingleResult();
    }
}
