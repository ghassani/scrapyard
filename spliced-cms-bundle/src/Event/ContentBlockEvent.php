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

use Spliced\Bundle\CmsBundle\Model\ContentBlockInterface;

class ContentBlockEvent extends Event
{
    protected $contentBlock;
    public function __construct(ContentBlockInterface $contentBlock)
    {
        $this->contentBlock = $contentBlock;
    }
    /**
     * @param ContentBlockInterface
     */
    public function setContentBlock(ContentBlockInterface $contentBlock)
    {
        $this->contentBlock = $contentBlock;
        return $this;
    }
    /**
     * @return ContentBlockInterface
     */
    public function getContentBlock()
    {
        return $this->contentBlock;
    }
}