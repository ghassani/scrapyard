<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * CustomerProfileInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CustomerProfileInterface
{

    public function getCustomer();

    public function setCustomer(CustomerInterface $customer);

    public function getFirstName();

    public function getLastName();

    public function getFullName();

    public function getFacebookId();

    public function getTwitterId();

    public function getYahooId();

    public function getGoogleId();
}
