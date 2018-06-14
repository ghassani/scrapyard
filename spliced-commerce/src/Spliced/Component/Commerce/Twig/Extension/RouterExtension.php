<?php

namespace Spliced\Component\Commerce\Twig\Extension;

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
            'commerce_path' => new \Twig_Function_Method($this, 'dbPath'),
            'file_exists' => new \Twig_Function_Method($this, 'fileExists'),
        );
    }

    /**
     *
     */
    public function dbPath($uri, array $parameters = array())
    {
        return $this->router->generate($uri, $parameters);
    }
    
    /**
     *
     * @param string $filePath
     */
    public function fileExists($filePath)
    {
        return file_exists($filePath);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_router';
    }

}
