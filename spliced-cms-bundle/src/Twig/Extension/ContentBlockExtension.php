<?php

namespace Spliced\Bundle\CmsBundle\Twig\Extension;

use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\TemplateRenderEvent;
use Spliced\Bundle\CmsBundle\Manager\TemplateManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ContentBlockExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentBlockExtension extends \Twig_Extension
{


    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array (
            new \Twig_SimpleFunction('get_block_path',       array($this, 'getBlockPath')),
            new \Twig_SimpleFunction('render_content_block', array($this, 'renderContentBlock'), array('is_safe' => array('html')))
        );
    }

    public function renderContentBlock($key, array $options = array())
    {
        $eventDispatcher = $this->container->get('event_dispatcher');
        $request = $this->container->get('request');

        $em = $this->container->get('doctrine.orm.entity_manager');

        $blockQuery =$em->getRepository('SplicedCmsBundle:ContentBlock')
            ->createQueryBuilder('b')
            ->leftJoin('b.template', 't')
            ->select('b, t')
            ->where('b.isActive = 1 AND b.blockKey = :key AND b.site = :site');


        $blockQuery->setParameter('key', $key);
        $blockQuery->setParameter('site', $this->container->get('spliced_cms.site_manager')->getCurrentSite()->getId());

        try{
            $block = $blockQuery
                ->getQuery()
                ->getSingleResult();

        } catch (NoResultException $e) {
            return false;
        }

        $event = $eventDispatcher->dispatch(
            Event::TEMPLATE_RENDER,
            new TemplateRenderEvent($request, $block->getTemplate(), null, array())
        );


        return $event->getResponse()->getContent();
    }

    public function getBlockPath($key)
    {
        $blockQuery = $this->getEntityManager()
            ->getRepository('SplicedCmsBundle:ContentBlock')
            ->createQueryBuilder('b, t')
            ->lefJoin('b.template', 't')
            ->select('b')
            ->where('b.isActive = 1');

        if (is_numeric($key)) {
            $blockQuery->andWhere('b.id = :key OR b.id = :key');
        } else {
            $blockQuery->andWhere('b.key = :key');
        }

        $blockQuery->setParameter('key', $key);

        $block = $blockQuery
            ->getQuery()
            ->getSingleResult();

        if (!$block) {
            return false;
        }

        return $this->getTemplateManager()->buildFilename($block->getTemplate());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms_content_block';
    }

    protected function getEntityManager()
    {
        return $this->em;
    }

    protected function getTemplateManager()
    {
        return $this->templateManager;
    }

}
