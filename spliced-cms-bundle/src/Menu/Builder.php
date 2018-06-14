<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;

class Builder extends ContainerAware
{
    
    protected $menuFactory;
    
    protected $em;

    public function __construct(FactoryInterface $menuFactory, EntityManager $em)
    {
        $this->menuFactory = $menuFactory;
        $this->em = $em;
    }

    public function createMainMenu( array $options = array())
    {
        $menu = $this->getMenuFactory()->createItem('spliced_cms.frontend');
        return $menu;
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
}