<?php
/*
* This file is part of the SplicedAdminThemeBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\AdminThemeBundle\Event;

class MenuBuilderEvent extends Event
{
    protected $menu = array();
    public function __construct(array $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @param array $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }
} 