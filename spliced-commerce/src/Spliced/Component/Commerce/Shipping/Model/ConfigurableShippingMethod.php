<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\Model;

use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

/**
 * ConfigurableShippingMethod
 * 
 * Use this abstract class to construct your own shipping method
 * which relies on options that can be changed by the user in the
 * administration area.
 * 
 * For more information about Configurable services, see:
 * 
 * Spliced\Component\Commerce\Configuration\ConfigurableInterface
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ConfigurableShippingMethod extends ShippingMethod implements ConfigurableInterface
{
    
    /**
     * Constructor
     */
    public function __construct($name, ShippingProviderInterface $provider)
    {
        parent::__construct($name, $provider);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.shipping.'.strtolower($this->getProvider()->getName()).'.'.strtolower($this->getName());
    }    

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->getOption('label',null);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLabel2()
    {
        return $this->getOption('label2',null);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAllowedCountries()
    {
        return $this->getOption('allowed_countries',array());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getExcludedCountries()
    {
        return $this->getOption('excluded_countries',array());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCost()
    {
        return number_format($this->getOption('cost',0.0),2);
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
}