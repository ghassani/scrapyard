<?php
/*
* This file is part of the SplicedAdminThemeBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\AdminThemeBundle\Menu;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;
use Spliced\Bundle\AdminThemeBundle\Event\Event;
use Spliced\Bundle\AdminThemeBundle\Event\MenuBuilderEvent;
use Symfony\Component\HttpFoundation\Request;

class Builder extends ContainerAware
{
    protected $eventDispatcher;
    protected $menuFactory;
    protected $em;
    
    public function __construct(FactoryInterface $menuFactory, EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->menuFactory = $menuFactory;
        $this->em = $em;
    }
    
    public function createMainMenu(Request $request, array $options = array())
    {
        $event = new MenuBuilderEvent(array());
        $this->getEventDispatcher()->dispatch(
            Event::BUILD_MAIN_MENU,
            $event
        );
        return $this->_buildMenu($event->getMenu());
    }
    
    public function createSlideOutMenu(Request $request, array $options = array())
    {
        $event = new MenuBuilderEvent(array());
        $this->getEventDispatcher()->dispatch(
            Event::BUILD_SLIDE_OUT_MENU,
            $event
        );
        return $this->_buildMenu($event->getMenu());
    }
    
    public function createUserMenu(Request $request, array $options = array())
    {
        $event = new MenuBuilderEvent(array());
        $this->getEventDispatcher()->dispatch(
            Event::BUILD_MAIN_USER,
            $event
        );
        return $this->_buildMenu($event->getMenu());
    }

    /**
     * @return FactoryInterface
     */
    protected function getMenuFactory()
    {
        return $this->menuFactory;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    
    protected function  _buildMenu(array $menu) {
        $root = $this->getMenuFactory()->createItem('main');
        ksort($menu);
        $buildItem = function($builder, $item, $key) use(&$buildItem, $root){
            if (isset($item['children'])) {
                $children = $item['children'];
                unset($item['children']);
            }
            $_builder = $builder->addChild($key, $item);
            if (isset($children) && count($children)) {
                ksort($children);
                foreach ($children as $childKey => $childItem) {
                    $buildItem($_builder, $childItem, $childKey);
                }
            }
        };
        foreach ($menu as $k => $item) {
            $buildItem($root, $item, $k);
        }
        return $root;
    }
}