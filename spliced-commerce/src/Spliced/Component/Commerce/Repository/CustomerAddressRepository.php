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
use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * CustomerAddressRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CustomerAddressRepository extends EntityRepository
{

    public function findAddressByIdForCustomer(CustomerInterface $customer, $address)
    {
        return $this->getEntityManager()->createQuery("SELECT a FROM SplicedCommerceBundle:CustomerAddress a
                WHERE a.customer = :customer
                AND a.id = :address")
          ->setParameter('customer', $customer)
          ->setParameter('address', $address)
          ->getSingleResult();

    }
}
