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
 * Route
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="route")
 * @ORM\Entity()
 */
abstract class Route implements RouteInterface
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
     * @var bigint $categoryId
     *
     * @ORM\Column(name="category_id", type="bigint", nullable=true)
     */
    protected $categoryId;

    /**
     * @ORM\OneToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @var bigint $productId
     *
     * @ORM\Column(name="product_id", type="bigint", nullable=true)
     */
    protected $productId;

    /**
     * @ORM\OneToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @var bigint $pageId
     *
     * @ORM\Column(name="page_id", type="bigint", nullable=true)
     */
    protected $pageId;

    /**
     * @ORM\OneToOne(targetEntity="ContentPage")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;

    /**
     * @var bigint $otherId
     *
     * @ORM\Column(name="other_id", type="bigint", nullable=true)
     */
    protected $otherId;

    /**
     * @var string $requestPath
     *
     * @ORM\Column(name="request_path", type="string", length=255, nullable=false)
     */
    protected $requestPath;

    /**
     * @var string $targetPath
     *
     * @ORM\Column(name="target_path", type="string", length=255, nullable=false)
     */
    protected $targetPath;

    /**
     * @var array $options
     *
     * @ORM\Column(name="options", type="array", length=255, nullable=true)
     */
    protected $options;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;


    /**
     * 
     */
    public function __construct()
    {
        $this->options = array();
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
     * setCategory
     *
     * @param CategoryInterface $category
     */
    public function setCategory(CategoryInterface $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * getCategory
     *
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    
    /**
     * Set product
     *
     * @param $route
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get getProduct
     *
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * setPage
     *
     * @param bigint $page
     */
    public function setPage(ContentPageInterface $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * getPage
     *
     * @return ContentPageInterface
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set otherId
     *
     * @param bigint $otherId
     */
    public function setOtherId($otherId)
    {
        $this->otherId = $otherId;

        return $this;
    }

    /**
     * Get otherId
     *
     * @return bigint
     */
    public function getOtherId()
    {
        return $this->otherId;
    }

    /**
     * Set requestPath
     *
     * @param string $requestPath
     */
    public function setRequestPath($requestPath)
    {
        $this->requestPath = preg_match('/^\//',$requestPath) ? $requestPath : '/'.$requestPath;

        return $this;
    }

    /**
     * Get requestPath
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }

    /**
     * Set targetPath
     *
     * @param string $targetPath
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;

        return $this;
    }

    /**
     * Get targetPath
     *
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * Set options
     *
     * @param string $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Add option
     *
     * @param string $option
     * @param string $value
     */
    public function addOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
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

}
