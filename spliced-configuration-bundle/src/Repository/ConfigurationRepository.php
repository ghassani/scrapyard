<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Spliced\Bundle\ConfigurationBundle\Model\ListFilter;

/**
 * ConfigurationRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationRepository extends EntityRepository
{
    public function getFilteredQuery(ListFilter $listFilter)
    {
        return  $this->createQueryBuilder('c');
    }
}
