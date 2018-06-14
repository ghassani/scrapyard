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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Spliced\Component\Commerce\Model\Order;
/**
 * OrderShipmentRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderShipmentRepository extends EntityRepository
{
    

    /**
     * getWebServiceQuery
     *
     * @return QueryBuilder
     */
    public function getWebServiceQuery()
    {
        return $this->createQueryBuilder('shipment')
        ->select('shipment, memos')
        ->leftJoin('shipment.memos','memos');
    }
}