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
 * ContentPageRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageRepository extends EntityRepository
{
    public function getFilteredQuery(ListFilter $listFilter)
    {
        return  $this->createQueryBuilder('c');
    }

    public function findBySlugForView($slug)
    {
        $query = $this->createQueryBuilder('c')
            ->select('
                c, t, v, l
            ')
            ->leftJoin('c.layout', 'l')
            ->leftJoin('c.template', 't')
            ->leftJoin('t.version',  'v')
            ->where('c.slug = :slug AND c.isActive = 1')
            ->setParameter('slug', $slug);
        try {
            $result = $query->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }
        return $result;
    }

    public function findByIdForView($id)
    {
        $query = $this->createQueryBuilder('c')
            ->select('
                c.id,
                t.id AS template_id,
                v.id AS version_id
            ')
            ->leftJoin('c.template', 't')
            ->leftJoin('t.version',  'v')
            ->where('c.id = :id')
            ->setParameter('id', $id);
            
        try {
            $result = $query->getQuery()
                ->getSingleResult(Query::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            return false;
        }

        return $result;
    }

    public function findAllBySiteForJson(Site $site)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.site = :site')
            ->setParameter('site', $site->getId());

        return $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);;
    }

}
