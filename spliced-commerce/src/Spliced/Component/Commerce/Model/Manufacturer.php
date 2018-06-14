<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Manufacturer
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="manufacturer")
 * @ORM\Entity()
 */
abstract class Manufacturer implements ManufacturerInterface
{
	/**
	 * @var bigint $id
	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
    protected $id;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=false)
     */
    protected $name;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", unique=false, nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="updated_at", type="datetime", unique=false, nullable=false)
     */
    protected $updatedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * __toString
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getName
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * setName
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    

    /**
     * getCreatedAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}