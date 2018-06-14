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
use Spliced\Component\Commerce\Doctrine\ODM\MongoDB\Mapping\Annotations as Commerce;

/**
 * Configuration
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="configuration")
 * @ORM\Entity()
 */
abstract class Configuration implements ConfigurationInterface
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
     * @var string $type
     *
     * @ORM\Column(name="data_type", type="string", length=100, unique=false, nullable=false)
     */
    protected $type;

    /**
     * @var string $key
     *
     * @ORM\Column(name="data_key", type="string", length=255, unique=true, nullable=false)
     */
    protected $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="data_value", type="text", unique=false, nullable=true)
     */
    protected $value;
    
    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    protected $label;

    /**
     * @var string $help
     *
     * @ORM\Column(name="help", type="string", length=255, nullable=true)
     */
    protected $help;
    
    /**
     * @var string $group
     *
     * @ORM\Column(name="parent_group", type="text", unique=false, nullable=true)
     */
    protected $group;
    
    /**
     * @var string $childGroup
     *
     * @ORM\Column(name="child_group", type="text", unique=false, nullable=true)
     */
    protected $childGroup;
    
    /**
     * @var string $position
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    protected $position;

    
    /**
     *  @var bool $required
     *  
     *  @ORM\Column(name="is_required", type="boolean", nullable=true)
     */
    protected $required; 

    /**
     *  @var DateTime $createdAt
     *  
     *  @ORM\Column(name="created_at", type="datetime", length=255, nullable=false)
     */
    protected $createdAt;
    
    /**
     *  @var DateTime $updatedAt
     *  
     *  @ORM\Column(name="updated_at", type="datetime", length=255, nullable=false)
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
        return $this->getValue();
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
     * {@inheritDoc}
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * getFormSafeKey
     * 
     * @return string
     */
    public function getFormSafeKey()
    {
        return str_replace('.', '_', $this->key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }  
    

    /**
     * {@inheritDoc}
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getGroup()
    {
        return $this->group;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setChildGroup($childGroup)
    {
        $this->childGroup = $childGroup;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildGroup()
    {
        return $this->childGroup;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setRequired($isRequired)
    {
        $this->required = $isRequired;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getRequired()
    {
        return $this->required;
    }
    
    /**
     * isRequired
     * 
     * Proxy method for getRequired
     * 
     * @return bool
     */
    public function isRequired()
    {
        return $this->getRequired();
    }


    /**
     * {@inheritDoc}
     */
    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getHelp()
    {
        return $this->help;
    }
    
}