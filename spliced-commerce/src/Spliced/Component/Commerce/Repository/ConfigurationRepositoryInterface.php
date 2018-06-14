<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Repository;

/**
 * ConfigurationRepositoryInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ConfigurationRepositoryInterface
{

    /**
     * getConfiguration
     * 
     * Loads the configuration as objects
     * 
     * @param bool $cache - To cache the query result or not
     * @param bool $hydrate - Return result as an array or as object
     */
    public function getConfiguration($cache = true, $hydrate = true);
    
}
