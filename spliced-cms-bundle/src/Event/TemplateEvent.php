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
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;

class TemplateEvent extends Event
{

    protected $template;

    public function __construct(TemplateInterface $template, SiteInterface $site = null)
    {
        $this->template = $template;
        $this->site = $site;
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
     * @param \Spliced\Bundle\CmsBundle\Model\SiteInterface $site
     */
    public function setSite($site = null)
    {
        $this->site = $site;
        return $this;
    }
    
    /**
     * @return \Spliced\Bundle\CmsBundle\Model\SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }
}