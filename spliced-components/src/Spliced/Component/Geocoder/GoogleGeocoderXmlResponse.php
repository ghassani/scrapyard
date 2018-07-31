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

use Spliced\Component\Geocoder\GeocoderResponseInterface;

/**
 * GoogleGeocoderXmlResponse
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleGeocoderXmlResponse implements GeocoderResponseInterface
{
    protected $response;
    
    public function __construct($response){
        $this->response = $response;
    }
    
    /**
     * getResponse
     *
     * @return SimpleXMLElement
     */
    public function getResponse(){
        return $this->response;
    }
    
    /**
     * getFormattedAddress
     *
     *  @return string
     */
    public function getFormattedAddress(){
        return (string) $this->response->result->formatted_address;
    }
    
    /**
     * getAddress
     *
     * @return string
     */
    public function getAddress(){
        return trim(preg_replace('/\s+/',' ',sprintf('%s %s',
            $this->extractAddressComponent('street_number'),
            $this->extractAddressComponent('route')
        )));
    }
    
    /**
     * getCity
     *
     * @return string
     */
    public function getCity(){
        return $this->extractAddressComponent('locality');
    }
    
    /**
     * getState
     *
     * @return string
     */
    public function getState(){
        return $this->extractAddressComponent('administrative_area_level_1');
    }
    
    /**
     * getZipcode
     *
     * @return string
     */
    public function getZipcode(){
        return $this->extractAddressComponent('postal_code');
    }
    
    /**
     * getZipcode
     *
     * @return string
     */
    public function getCountry($longName = true){
        return $this->extractAddressComponent('country', $longName);
    }
    
    /**
     * getLatitude
     *
     * @return string
     */
    public function getLatitude(){
        return (string) $this->response->result->geometry->location->lat;
    }
    
    /**
     * getLongitude
     *
     * @return string
     */
    public function getLongitude(){
        return (string) $this->response->result->geometry->location->lng;
    }
    
    /**
     * extractAddressComponent
     *
     * @param string $component
     */
    protected function extractAddressComponent($component, $longName = true){
        $accessKey = $longName == true ? 'long_name' : 'short_name';
        foreach($this->response->result->address_component as $addressComponent){
            if(is_array($addressComponent->type)){
                if($addressComponent->type[0] == $component){
                    return (string)$addressComponent->$accessKey;
                }
            } else if($addressComponent->type == $component){
                return (string)$addressComponent->$accessKey;
            }
        }
    }
    
    /**
     * isSuccess
     * 
     * @return bool
     */
    public function isSuccess(){
        return isset($this->response->status) && $this->response->status == 'OK';
    }
    
    /**
     * isError
     * 
     * @return bool
     */
    public function isError(){
        return !$this->isSuccess();
    }
    
    /**
     * getError
     * 
     * @return string
     */
    public function getError(){
        return isset($this->response->status) ? $this->response->status : null;
    }
}