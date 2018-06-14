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
 * Category
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="category")
 * @Gedmo\Tree(type="materializedPath")
 */
abstract class Category implements CategoryInterface
{

    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Gedmo\TreePathSource
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    protected $name;

    /**
     * @var string $name
     *
     * @ORM\Column(name="page_title", type="string", nullable=true, unique=false)
     */
    protected $pageTitle;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true, unique=false)
     */
    protected $description;

    /**
     * @var string $headTitle
     *
     * @ORM\Column(name="head_title", type="string", length=255, nullable=true, unique=false)
     */
    protected $headTitle;

    /**
     * @var string $metaDescription
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true, unique=false)
     */
    protected $metaDescription;

    /**
     * @var string $metaKeywords
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true, unique=false)
     */
    protected $metaKeywords;

    /**
    /**
     * @var string $urlSlug
     *
     * @ORM\Column(name="url_slug", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    protected $urlSlug;

    /**
     * @ORM\Column(name="tree_level", type="integer", nullable=true, unique=false)
     * @Gedmo\TreeLevel
     */
    protected $level;
    
    /**
     * @ORM\Column(name="tree_path", type="string", length=255, nullable=true, unique=false)
     * @Gedmo\TreePath
     */
    protected $path;

    /**
     * @ORM\Column(name="tree_left", type="integer", nullable=true, unique=false)
     * @Gedmo\TreeLeft
     */
    protected $left;
    
    /**
     * @ORM\Column(name="tree_right", type="integer", nullable=true, unique=false)
     * @Gedmo\TreeRight
     */
    protected $right;
    
    /**
     * @ORM\Column(name="position", type="integer", nullable=true, unique=false)
     */
    protected $position;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true, unique=false)
     */
    protected $isActive;
    
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false, unique=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, unique=false)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * @Gedmo\TreeParent
     */
    protected $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
        $date = new \DateTime('now');
        $this->setCreatedAt($date);
        $this->setUpdatedAt($date);
        $this->setIsActive(true);
        
    }
    
    /**
     * __toString
     */
    public function __toString()
    {
        return $this->getName();
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
     * Set parent_id
     *
     * @param bigint $parentId
     */
    public function setParent(CategoryInterface $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent_id
     *
     * @return bigint
     */
    public function getParent()
    {
        return $this->parent;
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
     */
    public function getRoutes()
    {
        return $this->route;
    }
    
    /**
     * Add child category
     *
     * @param CategoryInterface $child
     */
    public function addChild(CategoryInterface $child)
    {
    	if(!$this->hasChild($child)){
        	$this->children->add($child);
    	}
        return $this;
    }
    
    /**
     * hasChild
     *
     * @param CategoryInterface $child
     */
    public function hasChild(CategoryInterface $child)
    {
        return $this->children->contains($child);
    }
    
    /**
     * getChild
     *
     * @param CategoryInterface $child
     */
    public function getChild(CategoryInterface $child)
    {
        return $this->children->get($child);
    }
        
    /**
     * getChildren
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * setChildren
     *
     * @param Collection|array
     */
    public function setChildren($children)
    {
        if(!$children instanceof Collection){
            if(is_array($children)){
                $children = new ArrayCollection($children);
            } else {
                throw new \InvalidArgumentException('Children must be an array or instanceof Doctrine\Common\Collections\Collection');
            }    
        }
        
        $this->children = $children;
        return $this;
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
     * Set pageTitle
     *
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set headTitle
     *
     * @param string $headTitle
     */
    public function setHeadTitle($headTitle)
    {
        $this->headTitle = $headTitle;

        return $this;
    }

    /**
     * Get headTitle
     *
     * @return string
     */
    public function getHeadTitle()
    {
        return $this->headTitle;
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
     * Set urlSlug
     *
     * @param string $urlSlug
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    /**
     * Get urlSlug
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
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
    public function setUpdatedAt($updatedAt)
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
     * getPath
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * setPath
     * 
     * @param string
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * getLevel
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * setLevel
     *
     * @param string
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
    
    /**
     * getLeft
     *
     * @return string
     */
    public function getLeft()
    {
        return $this->left;
    }
    
    /**
     * setLeft
     *
     * @param string
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }
    

    /**
     * getRight
     *
     * @return string
     */
    public function getRight()
    {
        return $this->right;
    }
    
    /**
     * setRight
     *
     * @param string
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }
    
}