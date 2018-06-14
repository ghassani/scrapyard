<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Spliced\Bundle\CmsBundle\Model\ListFilter;

/**
 * LayoutRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LayoutRepository extends EntityRepository
{

    public function getFilteredQuery(listFilter $listFilter)
    {
        return  $this->createQueryBuilder('l');
    }

    public function findOneByIdForJson($id)
    {
        $query = $this->createQueryBuilder('l')
            ->select('l, lt, ltv, lcpt, lcptv')
            ->leftJoin('l.template', 'lt')
            ->leftJoin('lt.version', 'ltv')
            ->leftJoin('l.contentPageTemplate', 'lcpt')
            ->leftJoin('lcpt.version', 'lcptv')
            ->where('l.id = :id')
            ->setParameter('id', $id);
        
        return $query->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY);;
    }
}
