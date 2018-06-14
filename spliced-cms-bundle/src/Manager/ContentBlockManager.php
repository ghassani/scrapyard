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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use  Spliced\Bundle\CmsBundle\Event\Event;
use  Spliced\Bundle\CmsBundle\Event\ContentBlockEvent;
use  Spliced\Bundle\CmsBundle\Model\ContentBlockInterface;

/**
 * ContentBlockManager
 */
class ContentBlockManager
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
     * @param ContentBlockInterface $contentBlock
     * @param bool $flush
     * @return truw | Exception when save error encountered
     */
    public function save(ContentBlockInterface $contentBlock)
    {
        if ($contentBlock->getId()) {
            return $this->update($contentBlock);
        }
        $this->getEntityManager()->persist($contentBlock);
        $contentBlock->getTemplate()->setFilename(sprintf('blocks/%s.html.twig',
            $contentBlock->getBlockKey())
        );
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->save($contentBlock->getTemplate(), $contentBlock->getSite());
        // notify the event dispatcher that
        // it has been saved
        $this->getEventDispatcher()->dispatch(
            Event::CONTENT_BLOCK_SAVE,
            new ContentBlockEvent($contentBlock)
        );
        return true;
    }

    /**
     * @param ContentBlockInterface $contentBlock
     * @return mixed
     */
    public function update(ContentBlockInterface $contentBlock)
    {
        if (!$contentBlock->getId()) {
            return $this->save($contentBlock);
        }
        $this->getEntityManager()->persist($contentBlock);
        $contentBlock->getTemplate()->setFilename(sprintf('blocks/%s.html.twig',
            $contentBlock->getBlockKey())
        );
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->update($contentBlock->getTemplate(), $contentBlock->getSite());
        // notify the event dispatcher that
        // it has been updated
        $this->getEventDispatcher()->dispatch(
            Event::CONTENT_BLOCK_UPDATE,
            new ContentBlockEvent($contentBlock)
        );
        return true;
    }
    
    /**
     * @param $contentBlock - ContentBlockInterface or an array or Collection of them
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete($contentBlock)
    {
        if (is_array($contentBlock) || $contentBlock instanceof Collection) {
            foreach ($contentBlock as $cb) {
                if ($cb instanceof ContentBlockInterface) {
                    $this->getEntityManager()->remove($cb);
                } else {
                    throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of ContentBlockInterface'));
                }
            }
        } else if ($contentBlock instanceof ContentBlockInterface) {
            $this->getEntityManager()->remove($contentBlock);
        } else {
            throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of ContentBlockInterface'));
        }
        $this->getEntityManager()->flush();
        return $this;
    }
}