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
 * ProductAttributeOptionValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_attribute_option_value")
 * @ORM\Entity()
 */
class ProductAttributeOptionValue
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
     * @var string $value
     *
     * @ORM\Column(name="value", type="text", unique=false, nullable=false)
     */
    protected $value;

    /**
     * @var string $publicValue
     * 
     * @ORM\Column(name="public_value", type="text", unique=false, nullable=false)
     */
    protected $publicValue;
    
    /**
     * @var string $position
     * 
     * @ORM\Column(name="position", type="integer", unique=false, nullable=false)
     */
    protected $position;

    /**
     * @var float $priceAdjustment
     * 
     * @ORM\Column(name="price_adjustment", type="decimal", scale=12, precision=4, unique=false, nullable=false)
     */
    protected $priceAdjustment;

    /**
     * @var int $priceAdjustmentType
     * 
     * @ORM\Column(name="price_adjustment_type", type="smallint", unique=false, nullable=false)
     */
    protected $priceAdjustmentType;

    /**
     * @ORM\Column(name="value_data", type="array", unique=false, nullable=true)
     */
    protected $valueData = array();
    
    /**
     * @ORM\ManyToOne(targetEntity="ProductAttributeOption", inversedBy="values")
     * @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     */
    protected $option;

    /**
     * MongoDB\EmbedMany(targetDocument="ProductAttributeOptionValueProduct")
     */
    protected $products;
    
    /**
     * {@inheritDoc}
     */
    public function __construct(array $data = array())
    {
        $this->products = new ArrayCollection();
    }

    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set publicValue
     *
     * @param text $publicValue
     */
    public function setPublicValue($publicValue)
    {
        $this->publicValue = $publicValue;

        return $this;
    }

    /**
     * Get publicValue
     *
     * @return text
     */
    public function getPublicValue()
    {
        return $this->publicValue ? $this->publicValue : $this->value;
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
     * getPriceAdjustment
     *
     * @return float
     */
    public function getPriceAdjustment()
    {
        return $this->priceAdjustment;
    }

    /**
     * setPriceAdjustment
     *
     * @param float $priceAdjustment
     */
    public function setPriceAdjustment($priceAdjustment)
    {
        $this->priceAdjustment = $priceAdjustment;
        return $this;
    }

    /**
     * getPriceAdjustmentType
     */
    public function getPriceAdjustmentType()
    {
        return $this->priceAdjustmentType;
    }

    /**
     * setPriceAdjustmentType
     */
    public function setPriceAdjustmentType($priceAdjustmentType)
    {
        $this->priceAdjustmentType = $priceAdjustmentType;
        return $this;
    }

    /**
     * getProducts
     * 
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
    
    
    /**
     * addProduct
     *
     * @param ProductInterface $product
     */
    public function addProduct(ProductAttributeOptionValueProduct $product)
    {
        if(!$this->products->contains($product)){
            $this->products->add($product);
        }
        return $this;
    }

    /**
     * removeProduct
     *
     * @param ProductAttributeOptionValueProduct $product
     */
    public function removeProduct(ProductAttributeOptionValueProduct $product)
    {
        $this->products->removeElement($product);
        return $this;
    }
    
    /**
     * setProductsToAdd
     * 
     * @param Collection $productsToAdd
     */
    public function setProducts(Collection $products)
    {
        $this->products = $products;
        return $this;
    }
    
    /**
     * setValueData
     *
     * @param array $valueData
     */
    public function setValueData(array $valueData)
    {
        $this->valueData = $valueData;
        return $this;
    }
    
    /**
     * getValueData
     *
     * @return array
     */
    public function getValueData()
    {
        return $this->valueData;
    }
}
