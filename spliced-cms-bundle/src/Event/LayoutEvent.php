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

use Spliced\Bundle\CmsBundle\Model\LayoutInterface;

class LayoutEvent extends Event
{

    protected $layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param LayoutInterface
     */
    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
        return $this;
    }
    
    /**
     * @return LayoutInterface
     */
    public function getLayout()
    {
        return $this->layout;
    }
}