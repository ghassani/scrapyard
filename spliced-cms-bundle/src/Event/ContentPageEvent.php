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

use Spliced\Bundle\CmsBundle\Model\ContentPageInterface;

class ContentPageEvent extends Event
{

    protected $contentPage;

    public function __construct(ContentPageInterface $contentPage)
    {
        $this->contentPage = $contentPage;
    }

    /**
     * @param ContentPageInterface $contentPage
     */
    public function setContentPage(ContentPageInterface $contentPage)
    {
        $this->contentPage = $contentPage;
        return $this;
    }
    
    /**
     * @return ContentPageInterface
     */
    public function getContentPage()
    {
        return $this->contentPage;
    }
}