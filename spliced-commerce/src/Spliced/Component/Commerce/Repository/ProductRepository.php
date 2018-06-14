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
use Spliced\Component\Commerce\Model\CategoryInterface;

/**
 * ProductRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function findByCategoryQuery(CategoryInterface $category)
    {
        return $this->createQueryBuilder('product')
          ->leftJoin('product.categories', 'productCategories')
          ->where('product.isActive = 1 AND productCategories.category = :category')
          ->setParameter('category', $category->getId());
    }
}
