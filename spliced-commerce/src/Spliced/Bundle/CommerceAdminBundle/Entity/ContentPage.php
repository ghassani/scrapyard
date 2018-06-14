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
use Spliced\Component\Commerce\Model\ContentPage as BaseContentPage;

/**
 * @ORM\Table(name="content_page")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\ContentPageRepository")
 * @ORM\MappedSuperclass()
 */
class ContentPage extends BaseContentPage
{

}
