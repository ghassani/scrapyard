<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Geocoder;

/**
 * YahooAddressType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class YahooAddressType
{
    protected $response = array();
    
    /**
     * __construct
     * 
     * @param array $response
     */
    public function __construct(array $response){
        $this->response = $response;
    }
    

    /**
     * getFullAddress
     */
    public function getFullAddress(){
        return preg_replace('/\s+/',' ',sprintf('%s %s %s %s',
            $this->getLine1(),
            $this->getLine2(),
            $this->getLine3(),
            $this->getLine4()
        ));
    }
    
    /**
     * getFullWithoutZip5
     */
    public function getFullWithoutZip4(){
        return preg_replace(array(
            '/\s+/',
            '/(\-\d{4,5}|,|\.)/',
        ),
        array(
            ' ',
            '',
        )
        ,sprintf('%s %s %s %s',
            $this->getLine1(),
            $this->getLine2(),
            $this->getLine3(),
            str_replace($this->getCountry(), '', $this->getLine4())
        ));
    }
    
    /**
     * getLine1
     */
    public function getLine1(){
        return isset($this->response['line1']) ? $this->response['line1'] : null;
    }
    
    /**
     * getLine2
     */
    public function getLine2(){
        return isset($this->response['line2']) ? $this->response['line2'] : null;
    }
    
    /**
     * getLine3
     */
    public function getLine3(){
        return isset($this->response['line3']) ? $this->response['line3'] : null;
    }
    
    /**
     * getLine4
     */
    public function getLine4(){
        return isset($this->response['line4']) ? $this->response['line4'] : null;
    }
    
    /**
     * getAddress
     */
    public function getAddress(){
        return preg_replace('/\s+/',' ',sprintf("%s %s %s %s",
            $this->getHouse(),
            $this->getStreet(),
            $this->getUnitType(),
            $this->getUnit()
        ));
    }
    
    /**
     * getQuality
     */
    public function getQuality(){
        return isset($this->response['quality']) ? $this->response['quality'] : null;
    }
    
    /**
     *getHouse
     */
    public function getHouse(){
        return isset($this->response['house']) ? $this->response['house'] : null;
    }
    
    /**
     * getStreet
     */
    public function getStreet(){
        return isset($this->response['street']) ? $this->response['street'] : null;
    }
    
    /**
     * getUnitType
     */
    public function getUnitType(){
        return isset($this->response['unittype']) ? $this->response['unittype'] : null;
    }
    
    /**
     * getUnit
     */
    public function getUnit(){
        return isset($this->response['unit']) ? $this->response['unit'] : null;
    }
    
    /**
     * getCity
     */
    public function getCity(){
        return isset($this->response['city']) ? $this->response['city'] : null;
    }
    
    /**
     * getState
     */
    public function getState(){
        return isset($this->response['state']) ? $this->response['state'] : null;
    }
    
    /**
     * getStateCode
     */
    public function getStateCode(){
        return isset($this->response['statecode']) ? $this->response['statecode'] : null;
    }
    
    /** 
     * getZipcode
     */
    public function getZipcode(){
        return isset($this->response['postal']) ? $this->response['postal'] : null;
    }
    
    /**
     * getZip5
     */
    public function getZip5(){
        return isset($this->response['uzip']) ? $this->response['uzip'] : null;
    }

    /**
     * getZip4
     */
    public function getZip4(){
        $postals = explode('-',$this->response['postal']);
        return isset($postals[1]) ? $postals[1] : null;
    }
    
    /**
     * getCounty
     */
    public function getCounty(){
        return isset($this->response['county']) ? $this->response['county'] : null;
    }
    
    /**
     * getCountry
     */
    public function getCountry(){
        return isset($this->response['country']) ? $this->response['country'] : null;
    }
    
    /**
     * getCountryCode
     */
    public function getCountryCode(){
        return isset($this->response['countrycode']) ? $this->response['countrycode'] : null;
    }
    
    /**
     * getName
     */
    public function getName(){
        return isset($this->response['name']) ? $this->response['name'] : null;
    }
    
    /** 
     * getLatitude
     */
    public function getLatitude(){
        return isset($this->response['latitude']) ? $this->response['latitude'] : null;
    }
    
    /**
     * getLongitude
     */
    public function getLongitude(){
        return isset($this->response['longitude']) ? $this->response['longitude'] : null;
    }
    
    public function setField($field, $value){
        $this->response[$field] = $value;
    }
    
    
    public function getField($field){
        return isset($this->response[$field]) ? $this->response[$field] : null;
    }
}