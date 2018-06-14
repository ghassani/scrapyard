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
use Spliced\Bundle\CmsBundle\Model\MenuInterface;
use Spliced\Bundle\CmsBundle\Model\MenuItemInterface;
use Spliced\Bundle\CmsBundle\Model\MenuTemplateInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;

/**
 * Menu
 */
class Menu implements MenuInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $menuKey;
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $options = array();
    /**
     * @var bool
     */
    private $isActive;
    /**
     * @var SiteInterface
     **/
    private $site;
    /**
     * @var ArrayCollection
     **/
    private $items;
    /**
     * @var MenuTemplateInterace
     */
    private $menuTemplate;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $menuKey
     */
    public function setMenuKey($menuKey)
    {
        $this->menuKey = $menuKey;
        return $this;
    }
    /**
     * @return string
     */
    public function getMenuKey()
    {
        return $this->menuKey;
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
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
        return $this;
    }
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
     * @param mixed $items
     */
    public function setItems(ArrayCollection $items = null)
    {
        if(is_null($items)) {
            $this->items = new ArrayCollection();
            return $this;
        }
        foreach ($items as $item) {
            $item->setMenu($this);
        }
        $this->items = $items;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }
    public function addItem(MenuItemInterface $item)
    {
        $this->items->add($item->setMenu($this));
        return $this;
    }
    /**
     * @param MenuTemplateInterface $menuTemplate
     */
    public function setMenuTemplate(MenuTemplateInterface $menuTemplate = null)
    {
        $this->menuTemplate = $menuTemplate;
        return $this;
    }
    /**
     * @return MenuTemplateInterface
     */
    public function getMenuTemplate()
    {
        return $this->menuTemplate;
    }
    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
    /**
     * @return boolean
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
            'menuKey' => $this->getMenuKey(),
            'name' => $this->getName(),
            'options' => $this->getOptions(),
            'isActive' => $this->getIsActive(),
            'items' => array_map(function($item){
                return $item->toArray();
            }, $this->getItems()->toArray()),
            'menuTemplate' => $this->getMenuTemplate() ? $this->getMenuTemplate()->toArray() : array(),
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