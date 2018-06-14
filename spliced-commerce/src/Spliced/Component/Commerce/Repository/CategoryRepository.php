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
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

/**
 * CategoryRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CategoryRepository extends MaterializedPathRepository implements CategoryRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRoot($levels = 3)
    {
    	
        $query = $this->createQueryBuilder('category')
        ->select('category')
        ->where('category.isActive = 1');
        
        // join x levels of children
        $prevTo = null;
        for ($i = 1; $i <= $levels; $i++) {
        	$joinOn = $i == 1 ? 'category' : sprintf('children%s', $i);
        	$joinTo = sprintf('children%s', $i);
        	
        	$query->addSelect($joinTo)
        	  ->leftJoin(($prevTo ? $prevTo : $joinOn).'.children', $joinTo);
        	
        	$prevTo = $joinTo;
        }
        
        return $query->getQuery()
        ->execute();
    }
    
    /**
     * {@inheritDoc}
    
    public function getTree()
    {
        return $this->createQueryBuilder('category')
        ->field('parent')->exists(false)
        ->eagerCursor(true)
        ->getQuery()
        ->execute();
    } */
}
