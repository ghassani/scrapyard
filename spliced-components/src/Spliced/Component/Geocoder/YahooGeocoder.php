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


use Spliced\Component\Geocoder\GeocoderInterface;

/**
 * YahooGeocoder
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class YahooGeocoder implements GeocoderInterface{
    
    //const API_URL = 'http://where.yahooapis.com/geocode';
    const API_URL = 'http://query.yahooapis.com/v1/public/yql';
    
    
    protected $appId;
    
    protected $requestParameters = array( 
        'format' => 'json',
    );
    
    /**
     * 
     */
    public function __construct($appId){
        $this->requestParameters['appId'] = $appId;
    }
    
    /**
     * getUrl
     * Returns the URL with the given response format
     * 
     */
    public function getUrl(){
        return self::API_URL;
    }
    
    
    /**
     * 
     * @return 
     */
    public function getLocation(){
        return $this->requestParameters['q'];
    }

    /**
     * 
     * @param $address
     */
    public function setLocation($address){
        $this->requestParameters['q'] = sprintf("select * from geo.placefinder where text=\"%s\"",$address);
        return $this;
    }

    
    /**
     * 
     * @return 
     */
    public function getRequestParameters(){
        return $this->requestParameters;
    }

    
    /**
     * 
     */
    public function sendRequest(){
        
        $connectionOptions = array(
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_USERAGENT      => 'php-'.phpversion(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        
        if (!ini_get('open_basedir') && !ini_get('safe_mode')) {
            $connectionOptions[CURLOPT_FOLLOWLOCATION] = true;
        }
        
        $connection = curl_init(sprintf('%s?%s',$this->getUrl(),http_build_query($this->getRequestParameters(), null, '&')));
    
        curl_setopt_array($connection, $connectionOptions);// set options

        $response = curl_exec($connection);
        
        $responseHeaders = curl_getinfo($connection);
        
        $connectionError = curl_error($connection);
        
        curl_close($connection);
        
        if($connectionError){
            throw new \Exception($connectionError);
        }

        return new YahooGeocoderResponse($this->convertResponse($response));
    }
    
    /**
     * convertResponse
     * 
     * @param string $response
     * @return array
     */
    protected function convertResponse($response){
        return json_decode($response,true);
    }
    
    
    
}