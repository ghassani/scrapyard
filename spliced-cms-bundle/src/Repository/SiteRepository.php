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
 * SiteRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SiteRepository extends EntityRepository
{
    public function getFilteredQuery(listFilter $listFilter)
    {
        return  $this->createQueryBuilder('s');
    }
}
