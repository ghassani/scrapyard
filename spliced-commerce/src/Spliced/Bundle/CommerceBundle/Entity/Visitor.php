<?php

namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\Visitor as BaseVisitor;

/**
 * Visitor
 *
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceBundle\Repository\VisitorRepository")
 * @ORM\MappedSuperclass()
 */
class Visitor extends BaseVisitor
{
 
}
