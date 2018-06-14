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
 * ShippingMethodInterface
 * 
 * All shipping methods must implement this interface and must 
 * also be registered in the shipping manager to be known by the 
 * application.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ShippingMethodInterface
{
    
    /**
     * getProvider
     *
     * @return ShippingProviderInterface
     */
    public function getProvider();
    
    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel();

    /**
     * getLabel2
     *
     * @return string
     */
    public function getLabel2();

    /**
     * getAllowedCountries
     *
     * @return array
     */
    public function getAllowedCountries();

    /**
     * getExcludedCountries
     *
     * @return array
     */
    public function getExcludedCountries();

    /**
     * getPrice
     *
     * @return float
     */
    public function getPrice();

    /**
     * getCost
     *
     * @return float
     */
    public function getCost();
}
