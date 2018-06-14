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

class ConfigurationItemEvent extends Event
{
    /**
     * @var ConfigurationItemInterface
     */
    protected $configurationItem;
    
    /**
     * @param ConfigurationItemInterface $configurationItem
     */
    public function __construct(ConfigurationItemInterface $configurationItem)
    {
        $this->configurationItem = $configurationItem;
    }
    
    /**
     * @param Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface $configurationItem
     */
    public function setConfigurationItem(ConfigurationItemInterface $configurationItem)
    {
        $this->configurationItem = $configurationItem;
        return $this;
    }
    
    /**
     * @return Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface
     */
    public function getConfigurationItem()
    {
        return $this->configurationItem;
    }
} 