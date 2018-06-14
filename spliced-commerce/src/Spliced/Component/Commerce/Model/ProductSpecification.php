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
 * ProductSpecification
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_specification")
 * @ORM\Entity()
 */
abstract class ProductSpecification
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
     * @ORM\Column(name="position", type="integer", unique=false, nullable=true)
     */
    protected $position;
    
    /**
     * @var Product $product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="specifications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
	/**
	 * @var ProductSpecificationOption $option
	 *
	 * @ORM\ManyToOne(targetEntity="ProductSpecificationOption", inversedBy="productSpecifications")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
	 * })
	 */
	protected $option;

    
	/**
	 * @var ProductSpecificationOptionValue $value
	 *
	 * @ORM\ManyToOne(targetEntity="ProductSpecificationOptionValue")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="value_id", referencedColumnName="id")
	 * })
	 */
	protected $value;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = array();
    }
     
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @{inheritDoc}
     */
    public function setOption(ProductSpecificationOption $option)
    {
        $this->option = $option;
        $this->optionKey = $option->getKey();
        $this->optionType = $option->getOptionType();
        return $this;
    }

    /**
     * @{inheritDoc}
     */
    public function getOption()
    {
        return $this->option;
    }

        
    /**
     * @{inheritDoc}
     */
    public function setValue(ProductSpecificationOptionValue $value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * getProduct
     *
     * @return Product
    */
    public function getProduct()
    {
    	return $this->product;
    }

    /**
     * setProduct
     *
     * @param Product product
     *
     * @return self
    */
    public function setProduct(Product $product)
    {
	    $this->product = $product;
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
     * setPosition
     *
     * @param int position
     *
     * @return self
    */
    public function setPosition($position)
    {
	    $this->position = $position;
	    return $this;
    }
    

}