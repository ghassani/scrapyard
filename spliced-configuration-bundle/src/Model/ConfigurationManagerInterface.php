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

interface ConfigurationManagerInterface
{

    public function get($key);

    public function set($key, $value);

    public function has($key);

}