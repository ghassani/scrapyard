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

use Spliced\Bundle\CmsBundle\Model\SiteInterface;

class SiteEvent extends Event
{

    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    /**
     * @param SiteInterface
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
}