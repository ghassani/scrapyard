<?php

namespace Spliced\Bundle\CmsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Spliced\Bundle\CmsBundle\DependencyInjection\Compiler\TemplateExtensionCompilerPass;

class SplicedCmsBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplateExtensionCompilerPass());

    }


}
