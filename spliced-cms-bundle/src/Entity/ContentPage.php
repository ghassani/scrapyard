<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Bundle\CmsBundle\Model\ContentPageInterface;
use Spliced\Bundle\CmsBundle\Model\LayoutInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;

/**
 * ContentPage
 */
class ContentPage implements ContentPageInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pageKey;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var DateTime
     */
    private $createdAt;
    
    /**
     * @var DateTime
     */
    private $updatedAt;
    
    /**
     * @var TemplateInterface
     */
    private $template;

    /**
     * @var SiteInterface
     */
    private $site;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var ArrayCollection
     **/
    private $meta;

    /**
     * @var ArrayCollection
     **/
    private $routes;



    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->createdAt = new \DateTime();
    	$this->updatedAt = new \DateTime();
        $this->meta      = new ArrayCollection();
        $this->routes    = new ArrayCollection();
    }


    public function __toString()
    {
        return sprintf('%s - %s', $this->getPageKey(), $this->getName());
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
     * Set template
     *
     * @param TemplateInterface $template
     * @return ContentPage
     */
    public function setTemplate(TemplateInterface $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return TemplateInterface
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set site
     *
     * @param SiteInterface $site
     * @return ContentPage
     */
    public function setSite(SiteInterface $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set layout
     *
     * @param LayoutInterface $layout
     * @return ContentPage
     */
    public function setLayout(LayoutInterface $layout = null)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return LayoutInterface
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getName() {
		return $this->name;
	}

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
		$this->name = $name;
		return $this;
	}

    /**
     * @return \DateTime|DateTime
     */
    public function getCreatedAt() {
		return $this->createdAt;
	}

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt) {
		$this->createdAt = $createdAt;
		return $this;
	}

    /**
     * @return \DateTime|DateTime
     */
    public function getUpdatedAt() {
		return $this->updatedAt;
	}

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt) {
		$this->updatedAt = $updatedAt;
		return $this;
	}

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta)
    {
        foreach($meta as $m) {
            $m->setContentPage($this);
        }

        $this->meta = $meta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    public function addMeta(ContentPageMeta $meta)
    {
        $meta->setContentPage($this);
        $this->meta->add($meta);
        return $this;
    }

    /**
     * @param mixed $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }


    public function getCurrentContent()
    {
        return $this->getVersion() ? $this->getVersion()->getContent() : false;
    }

    /**
     * @param string $pageKey
     */
    public function setPageKey($pageKey)
    {
        $this->pageKey = $pageKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageKey()
    {
        return $this->pageKey;
    }

    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'pageKey' => $this->getPageKey(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'isActive' => $this->getIsActive(),
            'meta' => array_map(function($meta){
                return $meta->toArray();
            }, $this->getMeta()->toArray()),
            'template' => $this->getTemplate() ? $this->getTemplate()->toArray() : array(),
            'createdAt' => $this->getCreatedAt()->format('U'),
            'updatedAt' => $this->getUpdatedAt()->format('U'),
            'site' => $this->getSite() ? $this->getSite()->toArray() : array(),
        );
    }

    /**
     * @inheritDoc}
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }


}
