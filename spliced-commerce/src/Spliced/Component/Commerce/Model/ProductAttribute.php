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
 * ProductAttribute
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_attribute")
 * @ORM\Entity()
 */
abstract class ProductAttribute
{    
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var integer $productId
	 *
	 * @ORM\Column(name="product_id", type="integer")
	 */
	protected $productId;
	
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="option_id", type="integer")
	 */
	protected $optionId;
	
	/**
	 * @var Product
	 *
	 * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
	 * })
	 */
	protected $product;
	
	/**
	 * @var Option
	 *
	 * @ORM\ManyToOne(targetEntity="ProductAttributeOption")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
	 * })
	 */
	protected $option;
	
	/**
	 * @var Value
	 *
	 * @ORM\ManyToOne(targetEntity="ProductAttributeOptionValue")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="value_id", referencedColumnName="id")
	 * })
	 */
	protected $value;
	
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
	 *
	 */
	public function getOptionId()
	{
		return $this->optionId;
	}
	
	/**
	 * Get productId
	 *
	 * @return int
	 */
	public function getProductId()
	{
		return $this->productId;
	}


    /**
     * getOptionType
     * 
     * @return int
     */
    public function getOptionType()
    {
        return $this->optionKey;
    }
    
    /**
     * setOptionType
     * 
     * @param int $optionType
     */
    public function setOptionType($optionKey)
    {
        $this->optionKey = $optionKey;
        return $this;
    }

}
