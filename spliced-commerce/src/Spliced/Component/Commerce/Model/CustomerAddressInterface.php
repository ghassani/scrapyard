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
 * CustomerAddressInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CustomerAddressInterface
{
    /**
     * getCustomer
     * 
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * setCustomer
     * 
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer);

    /**
     * Set addressLabel
     *
     * @param string $addressLabel
     */
    public function setAddressLabel($addressLabel);

    /**
     * Get addressLabel
     *
     * @return string
     */
    public function getAddressLabel();

    /**
     * Set attn
     *
     * @param string $attn
     */
    public function setAttn($attn);

    /**
     * Get attn
     *
     * @return string
     */
    public function getAttn();

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address);

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set address2
     *
     * @param string $address2
     */
    public function setAddress2($address2);

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2();

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();
    /**
     * Set state
     *
     * @param string $state
     */
    public function setState($state);

    /**
     * Get state
     *
     * @return string
     */
    public function getState();

    /**
     * Set zipcode
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode);

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode();

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country);

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry();

    /**
     * setFirstName
     * 
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * getFirstName
     *
     * @return string
     */
    public function getFirstName();
    
    /**
     * setLastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * getLastName
     *
     * @return string
     */
    public function getLastName();
    
    /**
     * setPhoneNumber
     *
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber);
    
    /**
     * getPhoneNumber
     *
     * @return string
    */
    public function getPhoneNumber();
    
}
