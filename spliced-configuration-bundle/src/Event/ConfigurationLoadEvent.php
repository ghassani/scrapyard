<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Event;

use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;

class ConfigurationLoadEvent extends Event
{
    /**
     * @var array
     */
    protected $configuration;
    
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
    
    /**
     * @param array
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

}