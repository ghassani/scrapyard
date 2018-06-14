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
use  Spliced\Bundle\CmsBundle\Event\MenuTemplateEvent;
use  Spliced\Bundle\CmsBundle\Model\MenuTemplateInterface;

/**
 * MenuTemplateManager
 */
class MenuTemplateManager
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
     * @param MenuTemplateInterface $menuTemplate
     * @param bool $flush
     * @return truw | Exception when save error encountered
     */
    public function save(MenuTemplateInterface $menuTemplate)
    {
        if ($menuTemplate->getId()) {
            return $this->update($menuTemplate);
        }
        
        $this->getEntityManager()->persist($menuTemplate);
        
        // make sure we have a file name set for the template
        $menuTemplate->getTemplate()->setFilename(sprintf('menu_templates/%s.html.twig',
            $menuTemplate->getName()
        ));
        
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->save($menuTemplate->getTemplate(), $menuTemplate->getSite());
        
        // notify the event dispatcher that
        // it has been saved
        $this->getEventDispatcher()->dispatch(
            Event::MENU_TEMPLATE_SAVE,
            new MenuTemplateEvent($menuTemplate)
        );

        return true;
    }

    /**
     * @param MenuTemplateInterface $menuTemplate
     * @return mixed
     */
    public function update(MenuTemplateInterface $menuTemplate)
    {
        if (!$menuTemplate->getId()) {
            return $this->save($menuTemplate);
        }

        $this->getEntityManager()->persist($menuTemplate);
        
        // make sure we have a file name set for the template
        $menuTemplate->getTemplate()->setFilename(sprintf('menu_templates/%s.html.twig',
            $menuTemplate->getName()
        ));
        
        // save the template, also flushed the database
        // so we can skip that here
        $this->getTemplateManager()->update($menuTemplate->getTemplate(), $menuTemplate->getSite());
        
        // notify the event dispatcher that
        // it has been updated
        $this->getEventDispatcher()->dispatch(
            Event::MENU_TEMPLATE_UPDATE,
            new MenuTemplateEvent($menuTemplate)
        );
        
        return true;
    }

    /**
     * @param $menuTemplate - MenuTemplateInterface or an array or Collection of them
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete($menuTemplate)
    {
        if (is_array($menuTemplate) || $menuTemplate instanceof Collection) {
            
            foreach ($menuTemplate as $mt) {
                
                if ($mt instanceof MenuTemplateInterface) {
                    $this->getEntityManager()->remove($mt);
                } else {
                    throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of MenuTemplateInterface'));
                }
                
            }

        } else if ($menuTemplate instanceof MenuTemplateInterface) {
            $this->getEntityManager()->remove($menuTemplate);
        } else {
            throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of MenuTemplateInterface'));
        }
        
        $this->getEntityManager()->flush();

        return $this;
    }
}