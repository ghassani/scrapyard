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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Bundle\CmsBundle\Model\MenuTemplateInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;

/**
 * MenuTemplate
 */
class MenuTemplate implements MenuTemplateInterface
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
     * @var SiteInterface
     **/
    private $site;
    /**
     * @var TemplateInterface
     * })
     */
    private $template;
    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s - %s', $this->getId(), $this->getName());
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param SiteInterface $site
     */
    public function setSite(SiteInterface $site)
    {
        $this->site = $site;
        return $this;
    }
    /**
     * @return SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * @param TemplateInterface $template
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
        return $this;
    }
    /**
     * @return TemplateInterface
     */
    public function getTemplate()
    {
        return $this->template;
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
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'template' => $this->getTemplate() ? $this->getTemplate()->toArray() : array(),
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