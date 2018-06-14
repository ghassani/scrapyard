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
use Spliced\Component\Commerce\Model\VisitorInterface;

/**
 * SearchTermRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class SearchTermRepository extends EntityRepository
{

    /**
     * findExistingSearch
     * 
     * @param VisitorInterface $visitor
     * @param string $queryString
     */
    public function findExistingSearch(VisitorInterface $visitor, $queryString)
    {
        return $this->getEntityManager()->createQuery("SELECT term FROM SplicedCommerceBundle:SearchTerm term 
                WHERE term.visitor = :visitor and term.searchQuery = :searchQuery")
          ->setParameter('visitor', $visitor->getId())
          ->setParameter('searchQuery', $queryString)
          ->getSingleResult();
    }
    
}