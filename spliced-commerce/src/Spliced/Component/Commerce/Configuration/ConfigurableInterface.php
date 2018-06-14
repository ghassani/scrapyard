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

/**
 * ConfigurableInterface
 *
 * All services which implement this class and are tagged with:
 * 
 *                     commerce.configurable 
 *                     
 * will pass options to the configuration  manager related to the 
 * service in question in which the user(s) can edit and change 
 * configuration that effect various parts of the application.
 * 
 * For example, if you add a payment method but want an option for
 * the user to be able to turn that payment method off, then you would
 * extend this class, tag it in the service container and configuration
 * will be available in the administration area for the class in question.
 * 
 * For more information on the methods and parameters required/available to
 * a configuration entry, please see:
 * 
 * Spliced\Component\Commerce\Model\Configuration
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ConfigurableInterface
{

    /**
     * @return ConfigurationManager
     */
    public function getConfigurationManager();

    /**
     * getConfigPrefix
     *
     * @return string
     */
    public function getConfigPrefix();

    /**
     * getRequiredConfigurationFields
     *
     * @return array
     */
    public function getRequiredConfigurationFields();

    /**
     * getOptions
     * @return array
     */
    public function getOptions();

    /**
     * getOption
     * @param string $key
     *
     * @return mixed
     */
    public function getOption($key, $defaultValue = null);
}
