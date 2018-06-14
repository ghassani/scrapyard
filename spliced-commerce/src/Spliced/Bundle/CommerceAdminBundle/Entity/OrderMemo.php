<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\OrderMemo as BaseOrderMemo;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderMemo
 *
 * @ORM\Table(name="customer_order_memo")
 * @ORM\Entity
 * @ORM\MappedSuperclass()
 */
class OrderMemo extends BaseOrderMemo
{



}
