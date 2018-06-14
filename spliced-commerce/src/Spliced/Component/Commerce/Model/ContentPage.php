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
 * ContentPage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="content_page")
 * @ORM\Entity()
 */
abstract class ContentPage implements ContentPageInterface
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, unique=false, nullable=false)
     */
    protected $title;

    /**
     * @var string $pageTitle
     *
     * @ORM\Column(name="page_title", type="string", length=255, unique=false, nullable=true)
     */
    protected $pageTitle;

    /**
     * @var string $pageLayout
     *
     * @ORM\Column(name="page_layout", type="string", length=255, unique=false, nullable=true)
     */
    protected $pageLayout;

    /**
     * @var string $urlSlug
     *
     * @ORM\Column(name="url_slug", type="string", length=255, unique=true, nullable=false)
     */
    protected $urlSlug;

    /**
     * @var string $metaDescription
     *
     * @ORM\Column(name="meta_description", type="text", unique=false, nullable=true)
     */
    protected $metaDescription;

    /**
     * @var string $metaKeywords
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, unique=false, nullable=true)
     */
    protected $metaKeywords;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="text", unique=false, nullable=true)
     */
    protected $content;

    /**
     * @var bool $isActive
     *
     * @ORM\Column(name="is_active", type="boolean", unique=false, nullable=true)
     */
    protected $isActive;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", unique=false, nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="updated_at", type="datetime", unique=false, nullable=false)
     */
    protected $updatedAt;

    /**
     * __construct
     */
    public function __construct()
    {
        $date = new \DateTime('now');
        $this->setCreatedAt($date);
        $this->setUpdatedAt($date);
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
     * %}
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set pageLayout
     *
     * @param string $pageLayout
     */
    public function setPageLayout($pageLayout)
    {
        $this->pageLayout = $pageLayout;

        return $this;
    }

    /**
     * Get pageLayout
     *
     * @return string
     */
    public function getPageLayout()
    {
        return $this->pageLayout;
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
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

}
