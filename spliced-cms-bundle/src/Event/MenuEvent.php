<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Event;

use Spliced\Bundle\CmsBundle\Model\MenuInterface;

class MenuEvent extends Event
{
    
    protected $menu;

    public function __construct(MenuInterface $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @param MenuInterface
     */
    public function setMenu(MenuInterface $menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return MenuInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
    
}