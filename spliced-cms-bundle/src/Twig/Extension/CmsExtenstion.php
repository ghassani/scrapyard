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
        $this->siteManager = $container->get('spliced_cms.manager.site');
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'spliced_cms' => array(
                'site' => $this->siteManager->getCurrentSite()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
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
