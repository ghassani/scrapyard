<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\Fedex;

use Spliced\Component\Commerce\Shipping\Model\ConfigurableShippingMethod;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Shipping\Model\ShippingProvider;
use Spliced\Component\Commerce\Shipping\Model\ShippingProviderInterface;
use Spliced\Component\Commerce\Shipping\Model\ShippingMethodInterface;
use Spliced\Component\Commerce\Cart\CartManager;

/**
 * FlatRateShippingMethod
 * 
 * A basic flat-rate based shipping method for Fedex
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FlatRateShippingMethod extends ConfigurableShippingMethod
{
    /**
     * Constructor
     * 
     * @param string $name
     * @param ShippingProviderInterface $provider
     * @param array $defaultConfigurationValues
     */
    public function __construct($name, ShippingProviderInterface $provider, array $defaultConfigurationValues = array())
    {
        if(empty($name)){
            throw new \InvalidArgumentException('Fedex\FlatRateShippingMethod requires a value for argument $name');
        }
        
        
        $this->defaultConfigurationValues = $defaultConfigurationValues;
        
        parent::__construct($name, $provider);
    }  
    
    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->getProvider()->getConfigurationManager();
    }
    
    /**
     * getCartManager
     * 
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->getProvider()->getCartManager();
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
    
        return array(
            'label' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                'label' => 'Label',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 0,
                'required' => false,
            ),
            'label2' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                'label' => 'Label 2',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 1,
                'required' => false,
            ),
            'base_price' => array(
                'type' => 'float',
                'value' => isset($this->defaultConfigurationValues['base_price']) ? $this->defaultConfigurationValues['base_price'] : 0.00,
                'label' => 'Base Price',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 2,
                'required' => false,
            ),
            'cost' => array(
                'type' => 'float',
                'value' => isset($this->defaultConfigurationValues['cost']) ? $this->defaultConfigurationValues['cost'] : 0.00,
                'label' => 'Cost',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 3,
                'required' => false,
            ),
            'allowed_countries' => array(
                'type' => 'countries',
                'value' => isset($this->defaultConfigurationValues['allowed_countries']) ? $this->defaultConfigurationValues['allowed_countries'] : array(),
                'label' => 'Allowed Countries',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 4,
                'required' => false,
            ),
            'excluded_countries' => array(
                'type' => 'countries',
                'value' => isset($this->defaultConfigurationValues['excluded_countries']) ? $this->defaultConfigurationValues['excluded_countries'] : array(),
                'label' => 'Excluded Countries',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 5,
                'required' => false,
             ),
            'tracking_url' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['tracking_url']) ? $this->defaultConfigurationValues['tracking_url'] : null,
                'label' => 'Tracking URL',
                'help' => 'The URL To Track Shipments',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 6,
                'required' => false,
             ),
            'enabled' => array(
                'type' => 'boolean',
                'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : true,
                'label' => 'Enabled',
                'help' => '',
                'group' => 'Shipping',
                'child_group' => sprintf('%s/%s', ucwords($this->getProvider()->getName()), ucwords($this->getName())),
                'position' => 11,
                'required' => false,
            ),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPrice()
    {    
        return $this->getOption('base_price');
    }
        
}
