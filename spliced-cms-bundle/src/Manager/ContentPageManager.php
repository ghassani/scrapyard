<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\CmsBundle\Model\ContentPageInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use  Spliced\Bundle\CmsBundle\Event\Event;
use  Spliced\Bundle\CmsBundle\Event\ContentPageEvent;
use Doctrine\Common\Collections\Collection;

/**
 * ContentPageManager
 */
class ContentPageManager
{
    protected $em;
    protected $eventDispatcher;
    protected $templateManager;
    /**
     * @param EntityManager $em
     * @param EventDispatcherInterface $eventDispatcher
     * @param TemplateManager $templateManager
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher, TemplateManager $templateManager)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->templateManager = $templateManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return \Spliced\Bundle\CmsBundle\Manager\TemplateManager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * @param ContentPageInterface $contentPage
     * @param bool $flush
     * @return truw | Exception when save error encountered
     */
    public function save(ContentPageInterface $contentPage)
    {
        if ($contentPage->getId()) {
            return $this->update($contentPage);
        }
        
        $this->getEntityManager()->persist($contentPage);
        
        foreach($contentPage->getMeta() as $meta) {
            $meta->setContentPage($contentPage);
            $this->getEntityManager()->persist($meta);
        }
        
        // make sure we have a file name set for the template
        $contentPage->getTemplate()->setFilename(sprintf('pages/%s.html.twig',
            $contentPage->getPageKey()
        ));
        
        $this->getTemplateManager()->save(
            $contentPage->getTemplate(),
            $contentPage->getSite(),
            $contentPage->getLayout() ? $contentPage->getLayout()->getTemplate() : null
        );
        
        $this->getEventDispatcher()->dispatch(
            Event::CONTENT_PAGE_SAVE,
            new ContentPageEvent($contentPage)
        );

        return true;
    }

    /**
     * @param ContentPageInterface $contentPage
     * @return mixed
     */
    public function update(ContentPageInterface $contentPage)
    {
        if (!$contentPage->getId()) {
            return $this->save($contentPage);
        }

        $contentPage->setUpdatedAt(new \DateTime());
        
        $this->getEntityManager()->persist($contentPage);
        
        foreach($contentPage->getMeta() as $meta) {
            $meta->setContentPage($contentPage);
            $this->getEntityManager()->persist($meta);
        }
        
        $contentPage->getTemplate()->setFilename(sprintf('pages/%s.html.twig',
            $contentPage->getPageKey()
        ));
        
        $templateFilename = sprintf('pages/%s.html',
            $contentPage->getPageKey()
        );
        
        if (!$contentPage->getTemplate()->getFilename() || $templateFilename != $contentPage->getTemplate()->getFilename()) {
            $contentPage->getTemplate()->setFilename($templateFilename);
        }
        
        $this->getTemplateManager()->update(
            $contentPage->getTemplate(),
            $contentPage->getSite(),
            $contentPage->getLayout() ? $contentPage->getLayout()->getTemplate() : null
        );
        
        // dispatch event to notify of a content page update
        $this->getEventDispatcher()->dispatch(
            Event::CONTENT_PAGE_UPDATE,
            new ContentPageEvent($contentPage)
        );
        
        return true;
    }
    
    /**
     * @param $contentPage - ContentPageInterface or an array or Collection of them
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete($contentPage, $flush = true)
    {
        if (is_array($contentPage) || $contentPage instanceof Collection) {
            
            foreach ($contentPage as $cp) {
                if ($cp instanceof ContentPageInterface) {
                    
                    $this->getEntityManager()->remove($cp);
                    
                    if ($cp->getTemplate()) {
                        $this->getTemplateManager()->delete($cp->getTemplate(), $cp->getSite(), false);
                    }

                    $this->getEventDispatcher()->dispatch(
                        Event::CONTENT_PAGE_DELETE,
                        new ContentPageEvent($cp)
                    );

                } else {
                    throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of ContentPageInterface'));
                }
            }

        } else if ($contentPage instanceof ContentPageInterface) {
            if ($contentPage->getTemplate()) {
                $this->getTemplateManager()->delete($contentPage->getTemplate(), $contentPage->getSite(), false);
            }

            $this->getEntityManager()->remove($contentPage);
            
            $this->getEventDispatcher()->dispatch(
                Event::CONTENT_PAGE_DELETE,
                new ContentPageEvent($contentPage)
            );

        } else {
            throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of ContentPageInterface'));
        }

        if (true === $flush){
            $this->getEntityManager()->flush();
        }

        return $this;
    }
}