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
use Spliced\Component\Commerce\Model\NewsletterSubscriber as BaseNewsletterSubscriber;


/**
 * NewsletterSubscriber
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Table(name="newsletter_subscriber")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceBundle\Repository\NewsletterSubscriberRepository")
 * @ORM\MappedSuperclass()
 */
class NewsletterSubscriber extends BaseNewsletterSubscriber
{    


}