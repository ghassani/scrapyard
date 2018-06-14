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
use Spliced\Component\Commerce\Model\OrderPayment as BaseOrderPayment;
use Spliced\Component\Commerce\Model\OrderInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrderPayment
 *
 * @ORM\Table(name="customer_order_payment")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class OrderPayment extends BaseOrderPayment
{
    
}
