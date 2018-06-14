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
use Spliced\Bundle\CmsBundle\Entity\Site;
use Spliced\Bundle\CmsBundle\Model\ListFilter;

/**
 * ContentBlockRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentBlockRepository extends EntityRepository
{
    public function getFilteredQuery(listFilter $listFilter)
    {
        return  $this->createQueryBuilder('b');
    }

    public function findAllBySiteForJson(Site $site)
    {
        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.site = :site')
            ->setParameter('site', $site->getId());
            
        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);;
    }

}
