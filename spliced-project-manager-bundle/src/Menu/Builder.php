<?php

namespace Spliced\Bundle\ProjectManagerBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
    	$router = $this->container->get('router');
		
        $menu = $factory->createItem('root');
		$menu->setChildrenAttribute('class','inline');
        
		$menu->addChild('Projects', array('uri' => $router->generate('project_index')));
		$menu->addChild('Attributes', array('uri' => $router->generate('attribute_index')));
		$menu->addChild('Tags', array('uri' => $router->generate('tag_index')));
		$menu->addChild('Services', array('uri' => $router->generate('service_index')));
        $menu->addChild('Invoices', array('uri' => $router->generate('dashboard')));
		$menu->addChild('Clients', array('uri' => $router->generate('client_index')));
		$menu->addChild('Staff', array('uri' => $router->generate('staff_index')));
		$menu->addChild('Users', array('uri' => $router->generate('dashboard')));
		$menu->addChild('My Blog', array('uri' => $router->generate('dashboard')));
		$menu->addChild('Configuration', array('uri' => $router->generate('dashboard')));
		

        return $menu;
    }
	
}