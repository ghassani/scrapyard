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

/**
 * TemplateVersionRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TemplateVersionRepository extends EntityRepository
{

    public function findOneByIdForJson($id)
    {
        $query = $this->createQueryBuilder('l')
            ->select('l')
            ->where('l.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $query->getQuery()
                ->getSingleResult(Query::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            return false;
        }
        
        return $result;
    }

}
