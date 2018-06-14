<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Spliced\Bundle\CmsBundle\Event\ContentBlockEvent;

/**
 * ContentBlockEventListener
 *
 */
class ContentBlockEventListener
{
    public function onSave(ContentBlockEvent $event)
    {
    }

    public function onUpdate(ContentBlockEvent $event)
    {
    }
    
    public function onDelete(ContentBlockEvent $event)
    {
    }
}