<?php

namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\VisitorRequest as BaseVisitorRequest;

/**
 * VisitorRequest
 *
 * @ORM\Table(name="visitor_request")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class VisitorRequest extends BaseVisitorRequest
{
    
}