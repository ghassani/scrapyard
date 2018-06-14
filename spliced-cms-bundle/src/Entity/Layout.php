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

use Spliced\Bundle\CmsBundle\Model\LayoutInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;

/**
 * Layout
 */
class Layout implements LayoutInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $layoutKey;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var TemplateInterface
     */
    private $template;

    /**
     * @var TemplateInterface
     */
    private $contentPageTemplate;

    /**
     * @var SiteInterface
     */
    private $site;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Layout
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Layout
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set template
     *
     * @param TemplateInterface $template
     * @return Layout
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
     * Set contentPageTemplate
     *
     * @param TemplateInterface $template
     * @return Layout
     */
    public function setContentPageTemplate(TemplateInterface $contentPageTemplate = null)
    {
        $this->contentPageTemplate = $contentPageTemplate;

        return $this;
    }

    /**
     * Get contentPageTemplate
     *
     * @return TemplateInterface
     */
    public function getContentPageTemplate()
    {
        return $this->contentPageTemplate;
    }
    /**
     * Set site
     *
     * @param SiteInterface $site
     * @return Layout
     */
    public function setSite(SiteInterface $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $layoutKey
     */
    public function setLayoutKey($layoutKey)
    {
        $this->layoutKey = $layoutKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->layoutKey;
    }

    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'layoutKey' => $this->getLayoutKey(),
            'template' => $this->getTemplate() ? $this->getTemplate()->toArray() : array(),
            'contentPageTemplate' => $this->getContentPageTemplate() ? $this->getContentPageTemplate()->toArray() : array(),
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
