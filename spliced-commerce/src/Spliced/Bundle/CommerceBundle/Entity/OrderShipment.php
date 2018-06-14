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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Spliced\Component\Commerce\Model\OrderShipment as BaseOrderShipment;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * OrderShipment
 *
 * @ORM\Table(name="customer_order_shipment")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class OrderShipment extends BaseOrderShipment
{

}