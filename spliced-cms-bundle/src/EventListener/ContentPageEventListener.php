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
use Spliced\Bundle\CmsBundle\Event\ContentPageEvent;
use Spliced\Bundle\CmsBundle\Entity\Route;
use Doctrine\ORM\EntityManager;

/**
 * ContentPageEventListener
 *
 */
class ContentPageEventListener
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onSave(ContentPageEvent $event)
    {
        $contentPage = $event->getContentPage();
        // create a new route for this if possible
        $existingRoute = $this->getEntityManager()
            ->getRepository('SplicedCmsBundle:Route')
            ->findOneByRequestPath('/'.$contentPage->getSlug());
        
        if (!$existingRoute) {
            
            $route = new Route();

            $route->setTargetPath('SplicedCmsBundle:ContentPage:viewById')
                ->setRequestPath('/'.$contentPage->getSlug())
                ->setContentPage($contentPage)
                ->setName($contentPage->getPageKey())
                ->setParameters(array())
                ->setSite($contentPage->getSite());

            $this->getEntityManager()->persist($route);

            $this->getEntityManager()->flush();
        }
    }

    public function onUpdate(ContentPageEvent $event)
    {
    }

    public function onDelete(ContentPageEvent $event)
    {
    }

    protected function getEntityManager()
    {
        return $this->em;
    }
}