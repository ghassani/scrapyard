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

/**
 * NewsletterSubscriberRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class NewsletterSubscriberRepository extends EntityRepository
{

    /**
     *
     */
    public function findOneByEmail($email)
    {

        return $this->getEntityManager()
        ->createQuery("SELECT subscriber FROM SplicedCommerceBundle:NewsletterSubscriber subscriber 
                WHERE subscriber.email = :email")
                ->setParameter('email',$email)
        ->getSingleResult();
    }


}
