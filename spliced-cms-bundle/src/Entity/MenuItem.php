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
use Spliced\Bundle\CmsBundle\Model\MenuInterface;
use Spliced\Bundle\CmsBundle\Model\MenuItemInterface;

/**
 * MenuItem
 */
class MenuItem implements MenuItemInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var int
     */
    private $position;
    /**
     * @var string
     */
    private $targetPath;
    /**
     * @var string
     */
    private $anchorText;
    /**
     * @var string
     */
    private $titleText;
    /**
     * @var string
     */
    private $options = array();
    /**
     * @var MenuItemInterface
     **/
    private $parent;
    /**
     * @var MenuInterface
     **/
    private $menu;
    /**
     * @var ArryaCollection
     **/
    private $children;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->position = 0;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $anchorText
     */
    public function setAnchorText($anchorText)
    {
        $this->anchorText = $anchorText;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getAnchorText()
    {
        return $this->anchorText;
    }
    /**
     * @param Menu $menu
     */
    public function setMenu(MenuInterface $menu)
    {
        $this->menu = $menu;
        return $this;
    }
    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
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
     * @param MenuItem|null $parent
     */
    public function setParent(MenuItemInterface $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * @param mixed $targetPath
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }
    /**
     * @param mixed $titleText
     */
    public function setTitleText($titleText)
    {
        $this->titleText = $titleText;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTitleText()
    {
        return $this->titleText;
    }
    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }
    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'position' => $this->getPosition(),
            'targetPath' => $this->getTargetPath(),
            'anchorText' => $this->getAnchorText(),
            'titleText' => $this->getTitleText(),
            'children' => array_map(function($item){
                return $item->toArray();
            }, $this->getChildren()->toArray()),
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