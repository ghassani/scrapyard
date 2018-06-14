<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\ProductAttributeOption as BaseProductAttributeOption;

/**
 * @ORM\Table(name="product_attribute_option")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceBundle\Repository\ProductAttributeOptionRepository")
 * @ORM\MappedSuperclass()
 */ 
class ProductAttributeOption extends BaseProductAttributeOption
{

}