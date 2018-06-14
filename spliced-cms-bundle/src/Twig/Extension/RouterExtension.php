<?php

namespace Spliced\Bundle\CmsBundle\Twig\Extension;

use Symfony\Component\Routing\RouterInterface;

class RouterExtension extends \Twig_Extension
{

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('route', array($this, 'route')),
        );
    }

    /**
     *
     */
    public function route($uri, array $parameters = array())
    {
        return $this->router->generate($uri, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms_router';
    }

}