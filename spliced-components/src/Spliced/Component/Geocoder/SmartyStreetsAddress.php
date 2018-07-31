<?php

namespace Spliced\Component\Geocoder;

/**
 * Class SmartyStreetsAddress
 * @package Spliced\Component\Geocoder
 */
class SmartyStreetsAddress implements GeocoderAddressInterface{

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getSource()
    {
        return  'SmartStreets';
    }

    /**
     * @return mixed
     */
    public function getFullAddress($includeZip4 = true)
    {
        return preg_replace('/\s{2,}/', ' ', sprintf('%s %s %s %s %s',
            $this->getAddress(),
            $this->getAddress2(),
            $this->getCity(),
            $this->getState(),
            $includeZip4 === true ? $this->getFullZipcode() : $this->getZipcode()
        ));
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->data['delivery_line_1'];
    }

    /**
     * @return mixed
     */
    public function getAddress2()
    {
        return isset($this->data['delivery_line_2']) ? $this->data['delivery_line_2'] : null;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->data['components']['city_name'];
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->data['components']['state_abbreviation'];
    }

    /**
     * @return mixed
     */
    public function getZipcode()
    {
        return $this->data['components']['zipcode'];
    }

    /**
     * @return mixed
     */
    public function getZipcode4()
    {
        return $this->data['components']['plus4_code'];
    }

    /**
     * @return string
     */
    public function getFullZipcode()
    {
        return $this->data['components']['zipcode'].($this->data['components']['plus4_code'] ? '-'.$this->data['components']['plus4_code'] : null);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return 'US';
    }
}