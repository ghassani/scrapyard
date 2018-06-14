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
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Doctrine\ORM\Query\Expr;

/**
 * ProductAttributeOptionRepository
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ProductAttributeOptionRepository extends EntityRepository
{
    
}