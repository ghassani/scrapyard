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
use Spliced\Bundle\CmsBundle\Event\SiteEvent;
use Spliced\Bundle\CmsBundle\Manager\SiteHostManager;

/**
 * SiteEventListener
 *
 */
class SiteEventListener
{

	public function __construct(SiteHostManager $siteHostManager)
	{
		$this->siteHostManager = $siteHostManager;
	}

    public function onSave(SiteEvent $event)
    {
    	$this->siteHostManager->installFilesystem($event->getSite());
    	$this->siteHostManager->rebuildConfiguration($event->getSite());
    }

    public function onUpdate(SiteEvent $event)
    {

    }
    
    public function onDelete(SiteEvent $event)
    {

    }
}