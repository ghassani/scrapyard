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
use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\MenuEvent;
use Spliced\Bundle\CmsBundle\Model\MenuInterface;

/**
 * MenuManager
 */
class MenuManager
{

    protected $em;

    protected $eventDispatcher;

    /**
     * @param EntityManager $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
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
     * @param MenuInterface $menu
     * @param bool $flush
     * @return truw | Exception when save error encountered
     */
    public function save(MenuInterface $menu)
    {
        if ($menu->getId()) {
            return $this->update($menu);
        }
        $this->getEntityManager()->persist($menu);
        foreach ($menu->getItems() as $item) {
            $item->setMenu($menu);
        }
        $this->getEntityManager()->flush();
        // notify the event dispatcher that
        // it has been saved
        $this->getEventDispatcher()->dispatch(
            Event::MENU_SAVE,
            new MenuEvent($menu)
        );
        return true;
    }

    /**
     * @param MenuInterface $menu
     * @return mixed
     */
    public function update(MenuInterface $menu)
    {
        if (!$menu->getId()) {
            return $this->save($menu);
        }
        $this->getEntityManager()->persist($menu);
        foreach ($menu->getItems() as $item) {
            $item->setMenu($menu);
        }
        $this->getEntityManager()->flush();
        // notify the event dispatcher that
        // it has been updated
        $this->getEventDispatcher()->dispatch(
            Event::MENU_UPDATE,
            new MenuEvent($menu)
        );
        return true;
    }

    /**
     * @param $menu - MenuInterface or an array or Collection of them
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete($menu)
    {
        if (is_array($menu) || $menu instanceof Collection) {
            foreach ($menu as $m) {
                if ($m instanceof MenuInterface) {
                    $this->getEntityManager()->remove($m);
                } else {
                    throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of MenuInterface'));
                }
            }
        } else if ($menu instanceof MenuInterface) {
            $this->getEntityManager()->remove($menu);
        } else {
            throw new \InvalidArgumentException(sprintf('Must single, array, or Collection of MenuInterface'));
        }
        $this->getEntityManager()->flush();
        return $this;
    }
}