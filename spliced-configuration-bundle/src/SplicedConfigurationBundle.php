<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Spliced\Bundle\ConfigurationBundle\DependencyInjection\Compiler\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SplicedConfigurationBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TypeCompilerPass());
    }
}
