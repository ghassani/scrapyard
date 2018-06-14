<?php

namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;

class ConfigurationExtension extends \Twig_Extension
{

    /** ConfigurationManager **/
    protected $configurationManager;

    /**
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
       $this->configurationManager = $configurationManager;
    }

    /**
     * getConfigurationManager
     */
     protected function getConfigurationManager()
     {
         return $this->configurationManager;
     }
     
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_get_config' => new \Twig_Function_Method($this, 'getConfig'),
        );
    }

    /**
     * getConfig
     * @param string $key
     * @param string $defaultValue
     */
    public function getConfig($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get($key,$defaultValue);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_configuration';
    }

}
