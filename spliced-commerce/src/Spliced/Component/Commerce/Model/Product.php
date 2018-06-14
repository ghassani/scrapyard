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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 *
 * @ORM\Table(
 * 	name="product",
 * 	uniqueConstraints={
 * 		@ORM\UniqueConstraint(name="sku_idx", columns={"sku"}),
 * 		@ORM\UniqueConstraint(name="url_slug_idx", columns={"url_slug"})
 * 	}
 * )
 * @ORM\Entity()
 */
abstract class Product implements ProductInterface
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
     * @var string $sku
     *
     * @ORM\Column(name="sku", type="string", unique=true, length=75, nullable=false)
     */
    protected $sku;

    /**
     * @var string $manufacturerPart
     *
     * @ORM\Column(name="manufacturer_part", type="string", length=150, nullable=true)
     */
    protected $manufacturerPart;
    
    /**
     * @var smallint $type
     *
     * @ORM\Column(name="type", type="smallint", nullable=false)
     */
    protected $type;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;


    /**
     * @var string $name
     *
     * @ORM\Column(name="url_slug", type="string", length=255, unique=true, nullable=false)
     * @Gedmo\Slug(fields={"name"})
     */
    protected $urlSlug;

    /**
     * @var decimal $price
     *
     * @ORM\Column(name="price", type="decimal", scale=12, precision=4, nullable=false)
     */
    protected $price;

    /**
     * @var decimal $cost
     *
     * @ORM\Column(name="cost", type="decimal", scale=12, precision=4, nullable=false)
     */
    protected $cost;

    /**
     * @var decimal $specialPrice
     *
     * @ORM\Column(name="special_price", type="decimal", scale=12, precision=4, nullable=false)
     */
    protected $specialPrice;

    /**
     * @var boolean $isTaxable
     *
     * @ORM\Column(name="is_taxable", type="boolean", nullable=true)
     */
    protected $isTaxable;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    protected $isActive;

    /**
     * @var boolean $manageStock
     *
     * @ORM\Column(name="manage_stock", type="boolean", nullable=true)
     */
    protected $manageStock;
    
    /**
     * @var smallint $type
     *
     * @ORM\Column(name="availability", type="smallint", nullable=false)
     */
    protected $availability;

    /**
     * @var int $minOrderQuantity
     *
     * @ORM\Column(name="min_order_quantity", type="integer", nullable=false)
     */
    protected $minOrderQuantity;

    /**
     * @var int $maxOrderQuantity
     *
     * @ORM\Column(name="max_order_quantity", type="integer", nullable=false)
     */
    protected $maxOrderQuantity;

    /**
     * @var int $weight
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    protected $weight;
    
    /**
     * @var string $weight
     *
     * @ORM\Column(name="weight_units", type="string", length=4, nullable=true)
     */
    protected $weightUnits;
    
    /**
     * @var string $dimensions
     *
     * @ORM\Column(name="dimensions", type="string", length=50, unique=false, nullable=true)
     */
    protected $dimensions;
    
    /**
     * @var string $dimensionsUnits
     *
     * @ORM\Column(name="dimensions_units", type="string", length=4, unique=false, nullable=true)
     */
    protected $dimensionsUnits;
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;
    
    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="special_from_date", type="datetime", nullable=true)
     */
    protected $specialFromDate;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="special_to_date", type="datetime", nullable=true)
     */
    protected $specialToDate;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductContent", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $content = array();
    
    /**
     * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     * @ORM\OrderBy({"optionId" = "ASC"})
     */
    protected $attributes;

    /**
     * @ORM\OneToMany(targetEntity="ProductSpecification", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $specifications;
    
    /**
     * EmbedMany(targetDocument="ProductBundledItem")
     */
    protected $bundledItems;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $images;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductTierPrice", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     * @ORM\OrderBy({"minQuantity" = "ASC"})
     */
    protected $tierPrices;
    
    /**
     * ReferenceMany(targetDocument="Route")
     */
    protected $routes;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductRelatedProduct", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $relatedProducts;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="product", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $categories;

    /**
     * @ORM\OneToOne(targetEntity="Manufacturer", cascade={"persist"})
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    protected $manufacturer;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->type = ProductInterface::TYPE_PHYSICAL;
        $this->availability = ProductInterface::AVAILABILITY_IN_STOCK;
        $this->minOrderQuantity = 1;
        $this->manageStock = false;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        
        $this->content = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->bundledItems = new ArrayCollection();
        $this->tierPrices = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->relatedProducts = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->routes = new ArrayCollection();
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
     * setSku
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }
    
    /**
     * getSku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }
    
    /**
     * getType
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * setType
     * 
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * setName
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * setUrlSlug
     *
     * @param string $urlSlug
     *
     * @return Product
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    /**
     * getUrlSlug
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * Set manageStock
     *
     * @param boolean $manageStock
     */
    public function setManageStock($manageStock)
    {
        $this->manageStock = $manageStock;

        return $this;
    }

    /**
     * getUrlSlug
     *
     * @return boolean
     */
    public function getManageStock()
    {
        return $this->manageStock;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * getPrice
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set specialPrice
     *
     * @param decimal $specialPrice
     */
    public function setSpecialPrice($specialPrice)
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    /**
     * getSpecialPrice
     *
     * @return decimal
     */
    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }
    
    /**
     * hasSpecialPrice
     * 
     * @return bool
     */
    public function hasSpecialPrice()
    {

    	if($this->getSpecialPrice() && $this->getSpecialPrice() != $this->getPrice()){
    		$specialFrom = $this->getSpecialFromDate();
    		$specialTo = $this->getSpecialToDate();
    		$currentDate = new \DateTime();
    		
    		if($specialFrom && $specialTo && $specialFrom->getTimestamp() < $currentDate->getTimestamp() && $specialTo->getTimestamp() > $currentDate->getTimestamp()){
    			return true;
    		}
    	}
    	
    	return false;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set specialFromDate
     *
     * @param datetime $specialFromDate
     */
    public function setSpecialFromDate(\DateTime $specialFromDate = null)
    {
        $this->specialFromDate = $specialFromDate;

        return $this;
    }

    /**
     * Get specialFromDate
     *
     * @return datetime
     */
    public function getSpecialFromDate()
    {
        return $this->specialFromDate;
    }

    /**
     * Set specialToDate
     *
     * @param datetime $specialToDate
     */
    public function setSpecialToDate($specialToDate)
    {
        $this->specialToDate = $specialToDate;

        return $this;
    }

    /**
     * Get specialToDate
     *
     * @return datetime
     */
    public function getSpecialToDate()
    {
        return $this->specialToDate;
    }


    /**
     * getCost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }
    
    /**
     * setCost
     *
     * @param float $value
     */
    public function setCost($value = null)
    {
        $this->cost = $value;
    
        return $this;
    }
    
    /**
     * getIsTaxable
     *
     * @return bool
     */
    public function getIsTaxable()
    {
        return $this->isTaxable;
    }
    
    /**
     * setIsTaxable
     *
     * @param bool $value
     */
    public function setIsTaxable($value = null)
    {
        $this->isTaxable = $value;
    
        return $this;
    }
    
    
    
    /**
     * getAvailability
     *
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }
    
    /**
     * setAvailability
     *
     * @param string $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * getAvailabilityName
     *
     * @return string
     */
    public function getAvailabilityName()
    {
    
        switch($this->getAvailability()){
            default:
            case ProductInterface::AVAILABILITY_IN_STOCK:
                return 'In Stock';
                break;
            case ProductInterface::AVAILABILITY_UNAVAILABLE:
                return 'Unavailable';
                break;
            case ProductInterface::AVAILABILITY_BACKORDERED:
                return 'Backordered';
                break;
            case ProductInterface::AVAILABILITY_CALL_TO_ORDER:
                return 'Call to Order';
                break;
            case ProductInterface::AVAILABILITY_BUILT_TO_ORDER:
                return 'Built to Order';
                break;
    
        }
    }
    
    /**
     * getMaxOrderQuantity
     *
     * @return int|null
     */
    public function getMaxOrderQuantity()
    {
        return $this->maxOrderQuantity;
    }
    
    /**
     * setMaxOrderQuantity
     *
     * @param int $maxOrderQuantity
     */
    public function setMaxOrderQuantity($maxOrderQuantity = null)
    {
        $this->maxOrderQuantity = $maxOrderQuantity;
        return $this;
    }
    
    /**
     * getMinOrderQuantity
     *
     * @return int|null
     */
    public function getMinOrderQuantity()
    {
        return $this->minOrderQuantity;
    }
    
    /**
     * setMinOrderQuantity
     *
     * @param int $minOrderQuantity
     */
    public function setMinOrderQuantity($minOrderQuantity = null)
    {
        $this->minOrderQuantity = $minOrderQuantity;
        return $this;
    }
    


    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * getContent
     *
     * @return array|Collection
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * setContent
     * 
     * @param array|Collection
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * setContent
     *
     * @param ProductContent $content
     */
    public function addContent(ProductContent $content)
    {
        if(!$this->content->contains($content)){
            $this->content->add($content);
        }
        return $this;
    }
    
    /**
     * removeContent
     *
     * @param ProductContent $content
     */
    public function removeContent(ProductContent $content)
    {
        $this->content->removeElement($content);
        return $this;
    }
    
    /**
     * getContentByLanguage
     * 
     * @param string $language
     */
    public function getContentByLanguage($language = 'en')
    {
        foreach($this->getContent() as $content){
            if($language == $content->getLanguage()){
                return $content;
            }
        }
        return false;
    }
    

    /**
     * setAttributes
     */
    public function setAttributes($attributes){
        if(is_array($attributes)){
            $this->attributes = new ArrayCollection($attributes);
        } else if($attributes instanceof Collection){
            $this->attributes = $attributes;
        } else {
            throw new \Exception("Attributes Must be Array or ArrayCollection");
        }
        return $this;
    }
    
    /**
     * Get attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    
    /**
     * hasAttribute
     *
     * @param string $name
     */
    public function hasAttribute($name)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getOption()->getName() == $name) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * getAttribute
     *
     * @param string $name
     */
    public function getAttribute($name)
    {
        $return = new ArrayCollection();
    
        foreach ($this->attributes as $attribute) {
            $attributeOption = $attribute->getOption();
            if ($attributeOption->getName() == $name) {
                if ( $attributeOption->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_SINGLE_VALUE ) {
                    return $attribute;
                } elseif ( $attributeOption->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_MULTIPLE_VALUES ) {
                    $return->add($attribute);
                } else {
                    return $attribute;
                }
            }
        }
    
        return $return->count() ? $return : false;
    }
    
    /**
     * removeAttribute
     *
     * @param string $key
     */
    public function removeAttribute(ProductAttribute $productAttribute)
    {
        $this->attributes->removeElement($productAttribute);
        return $this;
    }
    
    /**
     * Add attribute
     *
     * @return Product
     */
    public function addAttribute(ProductAttribute $attribute)
    {
        if(!$this->attributes->contains($attribute)){
            $this->attributes->add($attribute);
        }
    
        return $this;
    }
    
    

    /**
     * setSpecifications
     */
    public function setSpecifications($specifications){
        if(is_array($specifications)){
            $this->specifications = new ArrayCollection($specifications);
        } else if($specifications instanceof Collection){
            $this->specifications = $specifications;
        } else {
            throw new \Exception("Specifications Must be Array or ArrayCollection");
        }
        return $this;
    }
    
    /**
     * getSpecifications
     * 
     * @return Collection
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    /**
     * getSpecification
     *
     * @param string $key - The specification key
     * 
     * @return bool - false when not found
     * @return ProductSpecification - When single value and found
     * @return ArrayCollection - When multi value and found
     */
    public function getSpecification($key)
    {
    	$return = new ArrayCollection();
    
    	foreach ($this->specifications as $specification) {
    		$specificationOption = $specification->getOption();
    		if (strtoupper($specificationOption->getKey()) == strtoupper($key)) {
    			if ( $specificationOption->getOptionType() == ProductSpecificationOptionInterface::OPTION_TYPE_SINGLE_VALUE ) {
    				return $specification;
    			} elseif ( $specificationOption->getOptionType() == ProductSpecificationOptionInterface::OPTION_TYPE_MULTIPLE_VALUE ) {
    				$return->add($specification);
    			} else {
    				return $specification;
    			}
    		}
    	} 
    
    	return $return->count() ? $return : false;
    }
    
    /**
     * removeSpecification
     *
     * @param ProductSpecification $productSpecification
     */
    public function removeSpecification(ProductSpecification $productSpecification)
    {
        $this->specifications->removeElement($productSpecification);
        return $this;
    }
    
    /**
     * addSpecification
     *
     * @return Product
     */
    public function addSpecification(ProductSpecification $specification)
    {
        if(!$this->specifications->contains($specification)){
            $this->specifications->add($specification);
        }
        return $this;
    }

    
    /**
     * getInformationalAttributes
     *
     * @return Collection
     */
    public function getInformationalAttributes()
    {
        $return = new ArrayCollection();
        foreach($this->getAttributes() as $attribute){
            if(in_array($attribute->getOptionType(), array(1,2))){
                $return->add($attribute);
            }
        }
        return $return;
    }

    /**
     * hasPriceAlteringAttributes
     *
     * @return bool
     */
    public function hasPriceAlteringAttributes()
    {
        if(!$this->getAttributes()){
            return false;
        }
        foreach($this->getAttributes() as $attr){
            if($attr->getOption()->getOptionType() == 3){
                foreach($attr->getOption()->getValues() as $value){
                    if($value->getPriceAdjustmentType()){
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * getPriceAlteringAttributes
     *
     * @return Collection
     */
    public function getPriceAlteringAttributes()
    {
        $return = new ArrayCollection();
        foreach($this->getAttributes() as $attribute){
            if($attribute->getOption()->getOptionType() == 3){
                $return->add($attribute);
            }
        }
        return $return;
    }
     
    /**
     *
     */
    public function hasUserDataAttributes()
    {
        foreach($this->attributes as $attr){
            if($attr->getOption()->getOptionType() == 3 || $attr->getOption()->getOptionType() == 4){
                return true;
            }
        }
        return false;
    }
    
    /**
     * getUserDataAttributes
     *
     * @return array
     */
    public function getUserDataAttributes()
    {
        $return = array();
        foreach($this->attributes as $attr) {
            if($attr->getOption()->getOptionType() == 3 || $attr->getOption()->getOptionType() == 4){
                $return[] = $attr;
            }
        }
        return $return;
    }
    
    /**
     * setBundledItems
     *
     * @param Collection $bundledItems
     */
    public function setBundledItems(Collection $bundledItems)
    {
        $this->bundledItems = $bundledItems;
        return $this;
    }
    
    /**
     * addBundledItem
     *
     * @param ProductBundledItem $item
     */
    public function addBundledItem(ProductBundledItem $item)
    {
        if(!$this->bundledItems->contains($item)){
            $this->bundledItems->add($item);
        }
        return $this;
    }
    
    /**
     * getBundledItems
     *
     * @return Collection
     */
    public function getBundledItems()
    {
        return $this->bundledItems;
    }
    
    /**
     * removeBundledItem
     *
     * @param ProductBundledItem $bundledItem
     */
    public function removeBundledItem(ProductBundledItem $bundledItem)
    {
        $this->bundledItems->removeElement($bundledItem);
        return $this;
    }
    

    /**
     * getImages
     *
     */
    public function getImages()
    {
        return $this->images;
    }
         
    /**
     * setImages
     *
     * @param Collection $images
     */
    public function setImages(Collection $images)
    {
        $this->images = $images;
        return $this;
    }
    
    /**
     * removeImage
     *
     * @return Product
     */
    public function removeImage(ProductImage $image)
    {
        $this->images->removeElement($image);
        return $this;
    }
    
    /**
     * Add images
     *
     * @return Product
     */
    public function addImage(ProductImage $image)
    {
        if(!$this->images->contains($image)){
            $image->setProduct($this);
            $this->images->add($image);
        }
        return $this;
    }
    

    /**
     * hasImage
     *
     * @return bool
     */
    public function hasImage($image)
    {
    	if($image instanceof ProductImage){
    		$image = $image->getFilePath().$image->getFileName();
    	}
    	
    	foreach($this->images as $_image){    		
    		if($image == $_image->getFilePath().$_image->getFileName()){
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * getMainImage
     *
     * Gets the first image in the collection marked as main
     * or the first image otherwise, null in the case of no images
     *
     * @return ProductImage|null
     */
    public function getMainImage()
    {
        foreach($this->getImages() as $image){
            if($image->getIsMain()){
                return $image;
            }
        }
        return $this->getImages()->first();
    }
    

    /**
     * getTierPrices
     * 
     * @return Collection
     */
    public function getTierPrices()
    {
        return $this->tierPrices;
         
    }
    
    /**
     * setTierPrices
     *
     * @param Collection $tierPrices
     */
    public function setTierPrices(Collection $tierPrices)
    {
        $this->tierPrices = $tierPrices;
        return $this;
    }
    
    /**
     * addTierPrice
     *
     * @param ProductTierPrice $tierPrice
     */
    public function addTierPrice(ProductTierPrice $tierPrice)
    {
    	
        if(!$this->tierPrices->contains($tierPrice)){
            $tierPrice->setProduct($this);
            $this->tierPrices->add($tierPrice);
        }                
        return $this;
    }
    
    /**
     * removeTierPrice
     *
     * @param ProductTierPrice $tierPrice
     */
    public function removeTierPrice(ProductTierPrice $tierPrice)
    {
        $this->tierPrices->removeElement($tierPrice);
        return $this;
    }

    
    /**
     * setRoutes
     *
     * @param Collection $routes
     */
    public function setRoutes(Collection $routes)
    {
        $this->routes = $routes;    
        return $this;
    }
    
    /**
     * getRoutes
     *
     * @return Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * addRoute
     * 
     * @param RouteInterface $route
     */
    public function addRoute(RouteInterface $route)
    {
        if (!$this->routes->contains($route)) {
            $this->routes->add($route);
        }
        return $this;
    }
    
    /**
     * removeRoute
     *
     * @param RouteInterface $route
     */
    public function removeRoute(RouteInterface $route)
    {
        $this->routes->removeElement($route);
        return $this;
    }
    
    /**
     * setRelatedProducts
     *
     * @param Collection $relatedProducts
     */
    public function setRelatedProducts(Collection $relatedProducts)
    {
        $this->relatedProducts = $relatedProducts;
        return $this;
    }
    
    /**
     * addRelatedProduct
     *
     * @param ProductInterface $relatedProduct
     */
    public function addRelatedProduct(ProductRelatedProduct $relatedProduct)
    {
        if(!$this->relatedProducts->contains($relatedProduct)){
        	$relatedProduct->setProduct($this);
            $this->relatedProducts->add($relatedProduct);
        }
        return $this;
    }
        
    /**
     * getRelatedProducts
     *
     * @return Collection
     */
    public function getRelatedProducts()
    {
        return $this->relatedProducts;
    }
    
    /**
     * removeRelatedProduct
     *
     * @param ProductInterface $relatedProduct
     */
    public function removeRelatedProduct(ProductRelatedProduct $relatedProduct)
    {
        $this->relatedProducts->removeElement($relatedProduct);
        return $this;
    }
    
    /**
     * Get categories
     *
     * @param Category $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * set categories
     *
     * @param Category $categories
     */
    public function setCategories($categories)
    {
        if(is_array($categories)){
            $this->categories = new ArrayCollection($categories);
        } else if($categories instanceof ArrayCollection){
            $this->categories = $categories;
        } else {
            throw new \Exception("Categories Must be Array or ArrayCollection");
        }
         
        return $this;
    }
    /**
     * addCategory
     *
     * @param CategoryInterface $category
     *
     * @return Product
     */
    public function addCategory(CategoryInterface $category )
    {
        $this->categories->add($category);
        return $this;
    }
     
    /**
     * hasCategory
     *
     * @param CategoryInterface $category
     *
     * @return Product
     */
    public function hasCategory(CategoryInterface $category)
    {
        foreach($this->getCategories() as $_category){
            if($_category->getCategory()->getId() == $category->getId()){
                return true;
            }
        }
        return false;
    }
    


    /**
     * getManufacturer
     * 
     * @return ManufacturerInterface
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }
    
    /**
     * setManufacturer
     *
     * @return ManufacturerInterface
     */
    public function setManufacturer(ManufacturerInterface $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }
    
    /**
     * setManufacturerPart
     *
     * @param string $manufacturerPart
     *
     * @return Product
     */
    public function setManufacturerPart($manufacturerPart)
    {
        $this->manufacturerPart = $manufacturerPart;
    
        return $this;
    }
    
    /**
     * getManufacturerPart
     *
     * @return string
     */
    public function getManufacturerPart()
    {
        return $this->manufacturerPart;
    }
    

    /**
     * getWeight
     * 
     * @return float|int|null
     */
    public function getWeight()
    {
        return $this->weight;
    }
    
    /**
     * setWeight
     *
     * @param float|int|null $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }
    
    /**
     * getDimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    /**
     * setDimensions
     *
     * @param array $dimensions
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
        return $this;
    }
    
    /**
     * getDimensionsUnits
     *
     * @return string
    */
    public function getDimensionsUnits()
    {
    	return $this->dimensionsUnits;
    }

    /**
     * setDimensionsUnits
     *
     * @param string dimensionsUnits
     *
     * @return self
    */
    public function setDimensionsUnits($dimensionsUnits)
    {
	    $this->dimensionsUnits = $dimensionsUnits;
	    return $this;
    }
    
    /**
     * getWeightUnits
     *
     * @return string
    */
    public function getWeightUnits()
    {
    	return $this->weightUnits;
    }

    /**
     * setWeightUnits
     *
     * @param string weightUnits
     *
     * @return self
    */
    public function setWeightUnits($weightUnits)
    {
	    $this->weightUnits = $weightUnits;
	    return $this;
    }
    
       
}