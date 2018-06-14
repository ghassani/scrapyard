<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping;


/**
 * ShippingAddress
 *
 * This is a data structure holding an address to be
 * shipped to for rate calculations.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShippingAddress
{
	
	/** @var string */
	public $address;

	/** @var string */
	public $address2;

	/** @var string */
	public $city;

	/** @var string */
	public $state;

	/** @var string */
	public $zipcode;
	
	/** @var string */
	public $country;
	

	/**
	 * Constructor
	 * 
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		foreach (array('address','address2','city','state','zipcode','country') as $availableField) {
			if(isset($data[$availableField]) && property_exists($this, $availableField)){
				$this->$availableField = $data[$availableField];
			}
		}
	}
	
	/**
	 * __toString
	 */
	public function __toString()
	{
		return sprintf('%s%s %s %s %s %s', 
			$this->getAddress(),
			$this->getAddress2() ? ' '.$this->getAddress2() : null,
			$this->getCity(),
			$this->getState(),
			$this->getZipCode(),
			$this->getCountry()
		);
	}

    /**
     * getAddress
     *
     * @return string
    */
    public function getAddress()
    {
    	return $this->address;
    }

    /**
     * setAddress
     *
     * @param string address
     *
     * @return self
    */
    public function setAddress($address)
    {
	    $this->address = $address;
	    return $this;
    }
    
    /**
     * getAddress2
     *
     * @return string
    */
    public function getAddress2()
    {
    	return $this->address2;
    }

    /**
     * setAddress2
     *
     * @param string address2
     *
     * @return self
    */
    public function setAddress2($address2)
    {
	    $this->address2 = $address2;
	    return $this;
    }
    
    /**
     * getCity
     *
     * @return string
    */
    public function getCity()
    {
    	return $this->city;
    }

    /**
     * setCity
     *
     * @param string city
     *
     * @return self
    */
    public function setCity($city)
    {
	    $this->city = $city;
	    return $this;
    }
    
    /**
     * getState
     *
     * @return string
    */
    public function getState()
    {
    	return $this->state;
    }

    /**
     * setState
     *
     * @param string state
     *
     * @return self
    */
    public function setState($state)
    {
	    $this->state = $state;
	    return $this;
    }

    /**
     * getZipcode
     *
     * @return string
    */
    public function getZipcode()
    {
    	return $this->zipcode;
    }

    /**
     * setZipcode
     *
     * @param string zipcode
     *
     * @return self
    */
    public function setZipcode($zipcode)
    {
	    $this->zipcode = $zipcode;
	    return $this;
    }
    
    /**
     * getCountry
     *
     * @return string
    */
    public function getCountry()
    {
    	return $this->country;
    }

    /**
     * setCountry
     *
     * @param string country
     *
     * @return self
    */
    public function setCountry($country)
    {
	    $this->country = $country;
	    return $this;
    }
    
}