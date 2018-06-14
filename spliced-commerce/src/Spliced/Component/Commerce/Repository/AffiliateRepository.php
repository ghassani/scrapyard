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
 * AffiliateRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class AffiliateRepository extends EntityRepository
{

    /**
     * getAll
     * 
     * @return Collection
     */
    public function getAll()
    {
        return $this->createQueryBuilder('affiliate')
          ->select('affiliate')
          ->getQuery()
          ->getResult();
    }
    
    /**
     * getAllActive
     * 
     * @return Collection
     */
    public function getAllActive()
    {
        return $this->createQueryBuilder('affiliate')
          ->select('affiliate')
		  ->where('affiliate.isActive = 1')
          ->orderBy('affiliate.sort', 'ASC')
          ->getQuery()
          ->getResult();
    }
    
    /**
     * findAffilliateByUrlPrefix
     *
     * @return AffiliateInterface
     */
    public function findAffilliateByUrlPrefix($urlPrefix)
    {
    	return $this->createQueryBuilder('affiliate')
    	->select('affiliate')
    	->where('affiliate.urlPrefix = :urlPrefix')
    	  ->setParameter('urlPrefix', $urlPrefix)
    	->orderBy('affiliate.sort', 'ASC')
    	->getQuery()
    	->getSingleResult();
    }
}
