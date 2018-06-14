<?php

namespace Spliced\Bundle\CmsBundle\Model;

interface MenuItemInterface
{
    /**
     * @return MenuInterface
     */
    public function getMenu();

    /**
     * @param MenuInterface $menu
     * @return MenuItemInterface
     */
    public function setMenu(MenuInterface $menu);

    /**
     * @return ArrayCollection
     */
    public function getChildren();

    /**
     * @return MenuInterface
     */
    public function getParent();

    /**
     * setParent
     *
     * @param MenuItemInterface $menuItem
     * @return MenuItemInterface
     */
    public function setParent(MenuItemInterface $menuItem);

    /**
     * toArray
     *
     * Turn the object into an array, recursively for relations
     *
     * @return array
     */
    public function toArray();

    /**
     * toJson
     *
     * Turn the object into a valid JSON string
     *
     * Ideally, encode toArray()
     *
     * @return string
     */
    public function toJson();
}