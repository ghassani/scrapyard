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
 * ProductContent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_content")
 * @ORM\Entity()
 */
class ProductContent
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
     * @var string $language
     *
     * @ORM\Column(name="language", type="string", length=4, nullable=false)
     */
    protected $language;
    
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;
    
    /**
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    protected $metaDescription;
    
    /**
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    protected $metaKeywords;
    
    /**
     * @ORM\Column(name="long_description", type="text", nullable=true)
     */
    protected $longDescription;
    
    /**
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    protected $shortDescription;
    
    /**
     * @ORM\Column(name="view_layout", type="text", nullable=true)
     */
    protected $viewLayout;
    
    
    /**
     * @ORM\Column(name="view_stylesheets", type="array", nullable=true)
     */
    protected $viewStylesheets = array();
    
    /**
     * @ORM\Column(name="view_javascripts", type="array", nullable=true)
     */
    protected $viewJavascripts = array();

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
    /**
     * Constructor
     * 
     * @param string $language
     */
    public function __construct($language = 'en')
    {
        $this->language = $language;
    }
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set metaDescription
     *
     * @param text $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }
    
    /**
     * Get metaDescription
     *
     * @return text
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }
    
    /**
     * Set metaKeywords
     *
     * @param text $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }
    
    /**
     * Get metaKeywords
     *
     * @return text
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }
    
    /**
     * Set longDescription
     *
     * @param text $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
        return $this;
    }
    
    /**
     * Get longDescription
     *
     * @return text
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }
    
    /**
     * Set shortDescription
     *
     * @param text $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }
    
    /**
     * Get shortDescription
     *
     * @return text
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }
     
    /**
     * getLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * setLanguage
     * @param $Language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * getName
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * setName
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    

    /**
     * getViewLayout
     */
    public function getViewLayout()
    {
        return $this->viewLayout;
    }
    
    /**
     * setViewLayout
     *
     * @param string $viewLayout
     */
    public function setViewLayout($viewLayout)
    {
        $this->viewLayout = $viewLayout;
        return $this;
    }
    
    /**
     * getViewStylesheets
     *
     * @return array
     */
    public function getViewStylesheets()
    {
        return $this->viewStylesheets;
    }
    
    /**
     * setViewStylesheets
     *
     * @param array $viewStylesheets
     */
    public function setViewStylesheets(array $viewStylesheets = array())
    {
        $this->viewStylesheets = $viewStylesheets;
        return $this;
    }
    
    /**
     * addViewStylesheet
     *
     * @param string $viewStylesheet - URL/URI/ResourcePath to Stylesheet
     */
    public function addViewStylesheet($viewStylesheet)
    {
        $this->viewStylesheets[] = $viewStylesheet;
        return $this;
    }
    
    /**
     * removeViewStylesheet
     *
     * @param string $viewStylesheet - URL/URI/ResourcePath to Stylesheet
     */
    public function removeViewStylesheet($viewStylesheet)
    {
        foreach ($this->viewStylesheets as $key => $_viewStylesheet) {
            if ($_viewStylesheet == $viewStylesheet) {
                unset($this->viewStylesheets[$key]);
            }
        }
        return $this;
    }
    
    /**
     * getViewJavascripts
     *
     * @return array
     */
    public function getViewJavascripts()
    {
        return $this->viewJavascripts;
    }
    
    /**
     * setViewJavascripts
     *
     * @param array $viewJavascripts
     */
    public function setViewJavascripts(array $viewJavascripts = array())
    {
        $this->viewJavascripts = $viewJavascripts;
        return $this;
    }
    
    /**
     * addViewJavascript
     *
     * @param string $viewJavascript - URL/URI/ResourcePath to Javascript
     */
    public function addViewJavascript($viewJavascript)
    {
        $this->viewJavascripts[] = $viewJavascript;
        return $this;
    }
    
    /**
     * removeViewJavascript
     *
     * @param string $viewJavascript - URL/URI/ResourcePath to Stylesheet
     */
    public function removeViewJavascript($viewJavascript)
    {
         foreach ($this->viewJavascripts as $key => $_viewJavascript) {
             if ($_viewJavascript == $viewJavascript) {
                 unset($this->viewJavascripts[$key]);
             }
         }
         return $this;
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
     * @param ProductInterface product
     *
     * @return self
     */
    public function setProduct(ProductInterface $product)
    {
    	$this->product = $product;
    	return $this;
    }
}