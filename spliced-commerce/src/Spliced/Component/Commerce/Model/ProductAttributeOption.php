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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductAttributeOption
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_attribute_option")
 * @ORM\Entity()
 */
abstract class ProductAttributeOption implements ProductAttributeOptionInterface
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
     * @var string $key
     *
     * @ORM\Column(name="key", type="string", length=75, unique=true, nullable=false)
     */
    protected $key;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @var string $publicName
     *
     * @ORM\Column(name="public_name", type="string", length=255, unique=false, nullable=true)
     */
    protected $publicName;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", unique=false, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="position", type="integer", unique=false, nullable=true)
     */
    protected $position;

    /**
     * @ORM\Column(name="option_type", type="smallint", unique=false, nullable=true)
     */
    protected $optionType;

    /**
     * @ORM\Column(name="option_data", type="array", unique=false, nullable=true)
     */
    protected $optionData;
   
    /**
     * @ORM\OneToMany(targetEntity="ProductAttributeOptionValue", mappedBy="option")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $values;

    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new ArrayCollection();
        $this->setOptionType(ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT);
        $this->optionData = array();
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
            case 3:
                return 'User Selection (Price Altering)';
                break;
            case 4:
                return 'User Input';
                break;
            default:
                return 'Undefined';
                break;
        }
    }
    
    /**
     * addValue
     *
     * @param ProductAttributeOptionValue $value
     */
    public function addValue(ProductAttributeOptionValue $value)
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
        }

        return $this;
    }
    
    /**
     * removeValue
     *
     * @param ProductAttributeOptionValue $value
     */
    public function removeValue(ProductAttributeOptionValue $value)
    {
        $this->values->removeElement($value);
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
     * getOptionData
     * 
     * @return array
     */
     public function getOptionData()
     {
         return $this->optionData;
     }
     
    
    /**
     * setOptionData
     * 
     * @param array $optionData
     */
     public function setOptionData(array $optionData = array())
     {
         $this->optionData = $optionData;
        return $this;
     }
     
}
