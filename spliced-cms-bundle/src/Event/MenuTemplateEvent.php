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

use Spliced\Bundle\CmsBundle\Model\MenuTemplateInterface;

class MenuTemplateEvent extends Event
{

    protected $menuTemplate;

    public function __construct(MenuTemplateInterface $menuTemplate)
    {
        $this->menuTemplate = $menuTemplate;
    }

    /**
     * @param MenuTemplateInterface
     */
    public function setMenuTemplate(MenuTemplateInterface $menuTemplate)
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
}