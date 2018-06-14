<?php

namespace Spliced\Bundle\CmsBundle\Twig\Extension;

use Spliced\Bundle\CmsBundle\Manager\TemplateManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ContentBlockExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CmsExtension extends \Twig_Extension
{

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'spliced_cms' => array(
                'site_manager'      => $this->container->get('spliced_cms.site_manager'),
                'template_manager'  => $this->container->get('spliced_cms.template_manager'),
                'history_manager'   => $this->container->get('spliced_cms.history_manager'),
                'gallery_manager'   => $this->container->get('spliced_cms.gallery_manager'),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
             // expose PHP's inspection functions
            new \Twig_SimpleFunction('is_int', 'is_int'),
            new \Twig_SimpleFunction('is_float', 'is_float'),
            new \Twig_SimpleFunction('is_numeric', 'is_numeric'),
            new \Twig_SimpleFunction('is_long', 'is_long'),
            new \Twig_SimpleFunction('is_null', 'is_null'),
            new \Twig_SimpleFunction('is_object', 'is_object'),
            new \Twig_SimpleFunction('is_callable', 'is_callable'),
            new \Twig_SimpleFunction('is_bool', 'is_bool'),
            new \Twig_SimpleFunction('is_array', 'is_array'),
            new \Twig_SimpleFunction('get_class', 'get_class'),           

            // expose some of PHP's serialization functions
            new \Twig_SimpleFunction('json_encode', 'json_encode'),
            new \Twig_SimpleFunction('json_decode', 'json_decode'),
            new \Twig_SimpleFunction('serialize', 'serialize'),
            new \Twig_SimpleFunction('unserialize', 'unserialize'),
 
            new \Twig_SimpleFunction('file_basename', 'basename'),
            new \Twig_SimpleFunction('file_dirname', 'dirname'),
        );
    }



    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms';
    }


}
