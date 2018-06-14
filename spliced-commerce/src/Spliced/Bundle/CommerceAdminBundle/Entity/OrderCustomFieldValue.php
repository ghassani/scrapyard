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
use Spliced\Component\Commerce\Model\OrderCustomFieldValue as BaseOrderCustomFieldValue;

/**
 * OrderCustomFieldValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Table(name="customer_order_custom_field_value")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class OrderCustomFieldValue extends BaseOrderCustomFieldValue
{


    
}