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

use Spliced\Bundle\CmsBundle\Model\ContentBlockInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;

/**
 * ContentBlock
 */
class ContentBlock implements ContentBlockInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $blockKey;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $isActive;

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
     * @var SiteIterface
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContentBlock
     */
    public function setCreatedAt($createdAt)
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
     * @return ContentBlock
     */
    public function setUpdatedAt($updatedAt)
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
     * @return ContentBlock
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
     * @return ContentBlock
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
     * @param string $blockKey
     */
    public function setBlockKey($blockKey)
    {
        $this->blockKey = $blockKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockKey()
    {
        return $this->blockKey;
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
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'blockKey' => $this->getBlockKey(),
            'name' => $this->getName(),
            'isActive' => $this->getIsActive(),
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
