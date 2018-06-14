<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Command\Template;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * RebuildCommand
 *
 * Rebuilds all Content related items with managed templates
 */
class RebuildCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('spliced:cms:templates-rebuild')
            ->setDescription('Rebuilds all Content related items with managed templates')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $templateManager = $this->getContainer()->get('spliced_cms.template_manager');
        $contentPageManager = $this->getContainer()->get('spliced_cms.content_page_manager');
        $contentBlockManager = $this->getContainer()->get('spliced_cms.content_block_manager');
        $layoutManager = $this->getContainer()->get('spliced_cms.layout_manager');
        $menuTemplateManager = $this->getContainer()->get('spliced_cms.menu_template_manager');
        
        $contentPages = $em->getRepository('SplicedCmsBundle:ContentPage')
            ->createQueryBuilder('r')
            ->select('r')
            ->getQuery()
            ->getResult();
        
        foreach ($contentPages as $contentPage) {
            $contentPageManager->update($contentPage);
            $templateManager->build(
                $contentPage->getTemplate(),
                $contentPage->getSite(),
                $contentPage->getLayout() ? $contentPage->getLayout()->getTemplate() : null
            );
        }

        $contentBlocks = $em->getRepository('SplicedCmsBundle:ContentBlock')
            ->createQueryBuilder('r')
            ->select('r')
            ->getQuery()
            ->getResult();
        
        foreach ($contentBlocks as $contentBlock) {
            $contentBlockManager->update($contentBlock);
            $templateManager->build(
                $contentBlock->getTemplate(),
                $contentBlock->getSite(),
                null
            );
        }

        $layouts = $em->getRepository('SplicedCmsBundle:Layout')
            ->createQueryBuilder('r')
            ->select('r')
            ->getQuery()
            ->getResult();
        
        foreach ($layouts as $layout) {
            $layoutManager->update($layout);
            $templateManager->build(
                $layout->getTemplate(),
                $layout->getSite(),
                null
            );
        }

        $menuTemplates = $em->getRepository('SplicedCmsBundle:MenuTemplate')
            ->createQueryBuilder('r')
            ->select('r')
            ->getQuery()
            ->getResult();
        
        foreach ($menuTemplates as $menuTemplate) {
            $menuTemplateManager->update($menuTemplate);
            $templateManager->build(
                $menuTemplate->getTemplate(),
                $menuTemplate->getSite(),
                null
            );
        }
    }
}