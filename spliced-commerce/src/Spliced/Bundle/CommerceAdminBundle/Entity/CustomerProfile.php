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

use Spliced\Component\Commerce\Model\CustomerProfile as BaseCustomerProfile;
use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerProfile
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Table(name="customer_profile")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 */
class CustomerProfile extends BaseCustomerProfile
{


}
