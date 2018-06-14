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

use Spliced\Component\Commerce\Repository\CustomerRepository as BaseCustomerRepository;

/**
 * CustomerRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerRepository extends BaseCustomerRepository
{

    public function getAdminListQuery()
    {
        return $this->createQueryBuilder('customer')
          ->select('customer, profile')
          ->leftJoin('customer.profile','profile');
    }
    
    public function getRecentlyCreated($limit = 10)
    {
        return $this->createQueryBuilder('customer')
        ->select('customer, profile')
        ->leftJoin('customer.profile','profile')
        ->orderBy('customer.id', 'DESC')
        ->getQuery()
        ->setMaxResults($limit)
        ->getResult();
    }
}
