<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\UPS;

use Spliced\Component\Commerce\Shipping\Model\ShippingProvider as BaseShippingProvider;

/**
 * Provider
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Provider extends BaseShippingProvider
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return BaseShippingProvider::PROVIDER_UPS;
    }

}
