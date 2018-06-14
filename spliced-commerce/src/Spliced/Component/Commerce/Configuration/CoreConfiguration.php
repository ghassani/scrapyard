<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration;

use Symfony\Component\Yaml\Yaml;

/**
 * CoreConfiguration
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CoreConfiguration implements ConfigurableInterface
{
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getConfigurationManager()->getByKeyExpression(sprintf('/^%s/',$this->getConfigPrefix()));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOption($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get(sprintf('%s.%s',$this->getConfigPrefix(),$key),$defaultValue);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $coreConfiguration = Yaml::parse(file_get_contents(dirname(__FILE__).'/../Resources/config/core_configuration.yml'));
        
        $config = array();
        $i = 0;
        foreach($coreConfiguration as $c){
            $key = preg_replace('/^commerce\./', '', $c['key']);
            $config[$key] = array(
                'type' => isset($c['type']) ? $c['type'] : 'string',
                'value' => isset($c['value']) ? $c['value'] : null,
                'label' => isset($c['label']) ? $c['label'] : null,
                'help' => isset($c['help']) ? $c['help'] : null,
                'group' => isset($c['group']) ? $c['group'] : null,
                'child_group' => isset($c['child_group']) ? $c['child_group'] : null,
                'position' => isset($c['position']) ? $c['position'] : null,
                'required' => isset($c['required']) ? $c['required'] : false,    
            );
        }

        return $config;
    }   
}
    