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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ProductSpecificationOption
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 */
abstract class ProductSpecificationOption implements ProductSpecificationOptionInterface
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
     * @ORM\Column(name="option_key", type="string", unique=true, length=75, nullable=false)
     */
    protected $key;
    
    /**
     * @ORM\Column(name="name", type="string", unique=false, length=255, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="public_name", type="string", unique=false, length=255, nullable=true)
     */
    protected $publicName;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="position", type="integer", unique=false, nullable=true)
     */
    protected $position;

    /**
     * @ORM\Column(name="option_type", type="smallint", unique=false, nullable=false)
     */
    protected $optionType;

    /**
     * @ORM\Column(name="filterable", type="boolean", unique=false, nullable=true)
     */
    protected $filterable;

    /**
     * @ORM\Column(name="on_view", type="boolean", unique=false, nullable=true)
     */
    protected $onView;
    
    /**
     * @ORM\Column(name="on_list", type="boolean", unique=false, nullable=true)
     */
    protected $onList;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductSpecificationOptionValue", mappedBy="option", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $values;


    /**
     * @ORM\OneToMany(targetEntity="ProductSpecification", mappedBy="option")
     */
    protected $productSpecifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setOptionType(static::OPTION_TYPE_SINGLE_VALUE);
        $this->onView = false;
        $this->onList = false;
        $this->filterable = false;
        $this->values = new ArrayCollection();
    } 

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }
    
    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * setOptionKey
     *
     * @param string $key
     */
    public function setOptionKey($key)
    {
    	return $this->setKey($key);
    }
    
    /**
     * Get key
     *
     * @return string
     */
    public function getOptionKey()
    {
    	return $this->getKey();
    }

    /**
     * Set publicName
     *
     * @param string $publicName
     */
    public function setPublicName($publicName)
    {
        $this->publicName = $publicName;

        return $this;
    }

    /**
     * Get publicName
     *
     * @return string
     */
    public function getPublicName()
    {
        return $this->publicName;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set onFront
     *
     * @param boolean $onFront
     */
    public function setFilterable($filterable)
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * Get filterable
     *
     * @return boolean
     */
    public function getFilterable()
    {
        return $this->filterable;
    }

    /**
     * setOnList
     *
     * @param boolean $onFront
     */
    public function setOnList($onList)
    {
        $this->onList = $onList;

        return $this;
    }

    /**
     * getOnList
     *
     * @return boolean
     */
    public function getOnList()
    {
        return $this->onList;
    }
    
    /**
     * setOnView
     *
     * @param boolean $onView
     */
    public function setOnView($onView)
    {
        $this->onView = $onView;

        return $this;
    }

    /**
     * getOnView
     *
     * @return boolean
     */
    public function getOnView()
    {
        return $this->onView;
    }
    /**
     * Set optionType
     *
     * @param int $optionType
     */
    public function setOptionType($optionType)
    {
        $this->optionType = $optionType;

        return $this;
    }

    /**
     * Get optionType
     *
     * @return int
     */
    public function getOptionType()
    {
        return $this->optionType;
    }
    
    /**
     * getOptionTypeName
     *
     * @return string
     */
    public function getOptionTypeName()
    {
        switch($this->getOptionType()){
            case 1:
                return 'Single Value';
                break;
            case 2:
                return 'Multi Value';
                break;
            default:
                return 'Undefined';
                break;
        }
    }

    /**
     * addValue
     *
     * @param ProductSpecificationOptionValue $value
     */
    public function addValue(ProductSpecificationOptionValue $value)
    {
        if(!$this->values->contains($value)){
        	$value->setOption($this);
            $this->values->add($value);
        }
        return $this;
    }
    
    /**
     * removeValue
     *
     * @param ProductSpecificationOptionValue $value
     */
    public function removeValue(ProductSpecificationOptionValue $value)
    {
        $this->values->removeElement($value);
        return $this;
    }
    
    /**
     * hasValue
     * 
     * Check for a value by string
     * 
     * @param bool $value
     */
    public function hasValue($value)
    {
        foreach ($this->values as $_value) {
            if (strtoupper($_value->getValue()) == strtoupper($value)) {
               return true; 
            }
        }
        return false;
    }
    
    /**
     * getValue
     *
     * Get a value by string
     *
     * @param string|bool $value
     */
    public function getValue($value)
    {
    	foreach ($this->values as $_value) {
    		if (strtoupper($_value->getValue()) == strtoupper($value)) {
    			return $_value;
    		}
    	}
    	return false;
    }
    
    /**
     * getValues
     *
     * @return Collection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * setValues
     *
     * @param Collection $values
     */
    public function setValues(Collection $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * setPosition
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * getPosition
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     *
     */
    public function getProductSpecifications()
    {
        return $this->productSpecifications;
    }

    public function setProductSpecifications($productSpecifications)
    {
        $this->productSpecifications = $productSpecifications;
    }
    
}