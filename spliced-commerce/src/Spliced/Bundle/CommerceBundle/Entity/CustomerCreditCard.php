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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Spliced\Component\Commerce\Model\CustomerCreditCard as BaseCustomerCreditCard;

/**
 * Spliced\CommerceBundle\Entity\CustomerCreditCard
 *
 * @ORM\Table(name="customer_credit_card")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @Assert\Callback(methods={
 *     "validateCreditCardNumber",
 *     "validateCreditCardExpiration",
 *     "validateCreditCardCvv"
 * }, groups={"validate_credit_card"})
 *
 */
class CustomerCreditCard extends BaseCustomerCreditCard
{

}
