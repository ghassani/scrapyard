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

use Spliced\Component\Commerce\Model\ContactMessage as BaseContactMessage;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactMessage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="contact_message")
 * @ORM\Entity()
 * @ORM\MappedSuperClass()
 */
class ContactMessage extends BaseContactMessage
{    
    
}