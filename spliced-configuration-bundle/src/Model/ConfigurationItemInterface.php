<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Model;

interface ConfigurationItemInterface
{
    /**
     * Returns a string with the associated data type
     *
     * @return string
     */
    public function getType();

    /**
     * Returns a unique string identifying this entry.
     *
     * @return string
     */
    public function getKey();
    
    /**
     * Returns the value
     *
     * @return mixed
     */
    public function getValue();
} 