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

/**
 * CustomerRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CustomerRepository extends EntityRepository
{

    public function findOneByEmail($email)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT customer, profile FROM SplicedCommerceBundle:Customer customer
              LEFT JOIN customer.profile profile
             WHERE customer.email = :email')
            ->setParameters(array('email' => $email))
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getSingleResult(); 

    }

    /**
     *
     */
    public function findOneByFacebookId($facebookId)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT customer, profile FROM SplicedCommerceBundle:Customer customer
            INNER JOIN customer.profile profile
            WHERE profile.facebookId = :facebookId')
            ->setParameters(array('facebookId' => $facebookId))
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getSingleResult();
    }

    /**
     *
     */
    public function findOneByTwitterId($twitterId)
    {
        return $this->getEntityManager()->createQuery(
                'SELECT customer, profile FROM SplicedCommerceBundle:Customer customer
                INNER JOIN customer.profile profile
                WHERE profile.twitterId = :twitterId')
                ->setParameters(array('twitterId' => $twitterId))
                ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->getSingleResult();
    }

    /**
     *
     */
    public function findOneByFacebookIdOrEmail($facebookId, $email)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT customer, profile FROM SplicedCommerceBundle:Customer customer
            LEFT JOIN customer.profile profile
            WHERE profile.facebookId = :facebookId OR customer.email = :email')
            ->setParameters(array('facebookId' => $facebookId, 'email' => $email))
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getSingleResult();
    }

    /**
     *
     */
    public function findOneByGoogleIdOrEmail($googleId, $email)
    {
        return $this->getEntityManager()->createQuery(
                'SELECT customer, profile FROM SplicedCommerceBundle:Customer customer
                LEFT JOIN customer.profile profile
                WHERE profile.googleId = :googleId OR customer.email = :email')
                ->setParameters(array('googleId' => $googleId, 'email' => $email))
                ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                ->getSingleResult();
    }

    /**
     *
     */
    public function findOneForView($customer)
    {
        return $this->getBaseQuery(true)
          ->where('customer = :customer')
          ->setParameter('customer', $customer)
          ->getQuery()
          ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
          ->getSingleResult();
    }

    /**
     *
     */
    public function getBaseQuery($forLookup = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
          ->select('customer, profile')
          ->from('SplicedCommerceBundle:Customer', 'customer')
          ->leftJoin('customer.profile','profile');

        if (false === $forLookup) {
            $qb->addSelect('addresses, preferedBillingAddress, preferedShippingAddress')
              ->leftJoin('customer.addresses')
              ->leftJoin('profile.preferedBillingAddress','preferedBillingAddress')
              ->leftJoin('profile.preferedShippingAddress','preferedShippingAddress');
        }

        return $qb;
    }

}
