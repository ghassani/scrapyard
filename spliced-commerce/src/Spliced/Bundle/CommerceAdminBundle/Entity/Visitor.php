<?php

namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\Visitor as BaseVisitor;

/**
 * Visitor
 *
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\VisitorRepository")
 * @ORM\MappedSuperclass()
 */
class Visitor extends BaseVisitor
{
 
}
