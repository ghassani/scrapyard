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
use  Spliced\Bundle\CmsBundle\Event\LayoutEvent;
use  Spliced\Bundle\CmsBundle\Model\LayoutInterface;

/**
 * LayoutManager
 */
class LayoutManager
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
     * @param LayoutInterface $layout
     * @param bool $flush
     * @return truw | Exception when save error encountered
     */
    public function save(LayoutInterface $layout)
    {
        if ($layout->getId()) {
            return $this->update($layout);
        }
        $this->getEntityManager()->persist($layout);
        // make sure we have a file name set for the template
        $layout->getTemplate()->setFilename(sprintf('layouts/%s.html.twig',
            $layout->getLayoutKey()
        ));
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->save($layout->getTemplate(), $layout->getSite());
        if ($layout->getContentPageTemplate()) {
            $this->getTemplateManager()->save($layout->getContentPageTemplate(), $layout->getSite(), null, false);
        }
        // notify the event dispatcher that
        // it has been saved
        $this->getEventDispatcher()->dispatch(
            Event::LAYOUT_SAVE,
            new LayoutEvent($layout)
        );
        return true;
    }

    /**
     * @param LayoutInterface $layout
     * @return mixed
     */
    public function update(LayoutInterface $layout)
    {
        if (!$layout->getId()) {
            return $this->save($layout);
        }
        $layout->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($layout);
        // make sure we have a file name set for the template
        $layout->getTemplate()->setFilename(sprintf('layouts/%s.html.twig',
            $layout->getLayoutKey()
        ));
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->update($layout->getTemplate(), $layout->getSite());
        if ($layout->getContentPageTemplate()) {
            $this->getTemplateManager()->update($layout->getContentPageTemplate(), $layout->getSite(), null, false);
        }
        // notify the event dispatcher that
        // it has been updated
        $this->getEventDispatcher()->dispatch(
            Event::LAYOUT_UPDATE,
            new LayoutEvent($layout)
        );
        return true;
    }
    
    /**
     * @param $layout - LayoutInterface or an array or Collection of them
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete($layout)
    {
        if (is_array($layout) || $layout instanceof Collection) {
            foreach ($layout as $l) {
                if ($l instanceof LayoutInterface) {
                    $this->getEntityManager()->remove($l);
                } else {
                    throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of LayoutInterface'));
                }
            }
        } else if ($layout instanceof LayoutInterface) {
            $this->getEntityManager()->remove($layout);
        } else {
            throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of LayoutInterface'));
        }
        $this->getEntityManager()->flush();
        return $this;
    }
}