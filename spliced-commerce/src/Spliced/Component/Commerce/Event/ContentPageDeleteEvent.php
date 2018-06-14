<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Spliced\Component\Commerce\Model\ContentPageInterface;

/**
 * ContentPageUpdateEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageDeleteEvent extends Event
{

    public function __construct(ContentPageInterface $content)
    {
        $this->content = $content;
    }

    /**
     * getContent
     *
     * @return ContentPageInterface
     */
    public function getContent()
    {
        return $this->content;
    }
}
