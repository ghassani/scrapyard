<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Analytics\AnalyticsManager;

class MultipleAppKernelExtension extends \Twig_Extension
{
    /**
     * Constructor
     * 
     * @param $container
     */
    public function __construct($container)
    {
       $this->routerGenerator = $container->get('multiapp.routing_generator');
    }

    /**
     * getRouterGenerator
     * 
     * @return mixed
     */
    protected function getRouterGenerator()
    {
        return $this->routerGenerator;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(

        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'multiple_app_kernel_generate_route' => new \Twig_Function_Method($this, 'multipleAppKernelGenerateRoute'),
        );
    }

    /**
     * multipleAppKernelGenerateRoute
     * 
     */
    public function multipleAppKernelGenerateRoute($appName, $routeName, $routeParameters = array(), $absolute = false)
    {
        return $this->getRouterGenerator()->generate($appName, $routeName, $routeParameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_analytics';
    }

}
