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
 * ProductRelatedProduct
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 */
class ProductRelatedProduct
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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="relatedProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
    /**
     * @var Product
     *
     * @ORM\OneToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(name="related_product_id", referencedColumnName="id")
     */
    protected $relatedProduct;
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getProduct
     * 
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * setProduct
     * 
     * @param ProductInterface $product
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
        return $this;
    }    
        
    /**
     * getRelatedProduct
     *
     * @return ProductInterface
    */
    public function getRelatedProduct()
    {
    	return $this->relatedProduct;
    }

    /**
     * setRelatedProduct
     *
     * @param ProductInterface relatedProduct
     *
     * @return self
    */
    public function setRelatedProduct($relatedProduct)
    {
	    $this->relatedProduct = $relatedProduct;
	    return $this;
    }
    
    
}