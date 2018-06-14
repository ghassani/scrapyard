<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Spliced\Component\Commerce\DependencyInjection\BaseConfiguration;

/**
 * Configuration
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Configuration extends BaseConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {    
        return $this->buildConfigurationTree(false);
    }

}