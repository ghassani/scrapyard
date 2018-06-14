<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Twig\Extension;

use Spliced\Bundle\ConfigurationBundle\Manager\ConfigurationManager;

/**
 * ConfigurationExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationExtension extends \Twig_Extension
{

    /**
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('spliced_configuration_get', array($this, 'get')),
            new \Twig_SimpleFunction('spliced_configuration_has', array($this, 'has')),
            new \Twig_SimpleFunction('spliced_configuration_set', array($this, 'set')),
            new \Twig_SimpleFunction('spliced_configuration_get_type', array($this, 'getType')),
        );
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->getConfigurationManager()->get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->getConfigurationManager()->has($key);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->getConfigurationManager()->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms_configuration';
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getType($name)
    {
        return $this->getConfigurationManager()->getType($name);
    }
    
    /**
     * @return ConfigurationManager
     */
    private function getConfigurationManager()
    {
        return $this->configurationManager;
    }
}
