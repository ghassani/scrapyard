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
 * YahooGeocoderResponse
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class YahooGeocoderResponse implements GeocoderResponseInterface
{
    protected $response = array();
    
    public function __construct($response){
        $this->response = $response['query'];
    }
    
    public function getResults(){
        if(isset($this->userSetResults)){
            return $this->userSetResults;
        }
        $return = array();
        foreach($this->response['results'] as $result){
            $return[] = new YahooAddressType($result);
        }
        return $return;
    }
    
    public function getFirst()
    {
        foreach($this->getResults() as $result){
            return $result;
        }
        return null;
    }
    
    public function setResults(array $results){
        $this->userSetResults = $results;    
    }
    
    public function matchedAddress(){
        foreach($this->getResults() as $result){
            if($result->getLine1()){
                return true;
            }
        }
        return false;
    }
    /**
     * getResponse
     * 
     * @return array
     */
    public function getResponse(){
        return $this->response;
    }
    
    /**
     * 
     */
    public function isSuccess(){
        return $this->response['count'] > 0;
    }
    
    /**
     * isError
     */
    public function isError(){
        return $this->response['count'] == 0;
    }
    
    /**
     * getError
     */
    public function getError(){
        return $this->response['ErrorMessage'];
    }
    
    /**
     * 
     */
    public function getFound(){
        return $this->response['count'];
    }
}