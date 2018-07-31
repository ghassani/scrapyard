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
use Spliced\Component\Geocoder\GoogleGeocoderResponse;

/**
 * GoogleGeocoder
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleGeocoder implements GeocoderInterface{
    
    const API_URL = 'https://maps.googleapis.com/maps/api/geocode';
    
    protected $responseFormat;
    
    protected $responseFormats = array('json','xml');
    
    protected $requestParameters = array( 
        'language' => 'en', 
        'sensor' => 'false',
    );
    
    /**
     * 
     */
    public function __construct($responseFormat = 'json'){
        if(!in_array($responseFormat,$this->responseFormats)){
            throw new \Exception(sprintf('Google API accepts %s response formats, you supplied %s',
                    implode(',',$this->responseFormats),$responseFormat));
        }
        $this->responseFormat = $responseFormat;
    }
    
    /**
     * getUrl
     * Returns the URL with the given response format
     * 
     */
    public function getUrl(){
        return sprintf('%s/%s',self::API_URL,$this->responseFormat);
    }
    
    
    /**
     * 
     * @return 
     */
    public function getAddress(){
        return $this->requestParameters['address'];
    }

    /**
     * 
     * @param $address
     */
    public function setAddress($address){
        $this->requestParameters['address'] = $address;
        return $this;
    }

    /**
     * 
     * @return 
     */
    public function getLatlng(){
        return $this->requestParameters['latlng'];
    }

    /**
     * 
     * @param $atlng
     */
    public function setLatlng($latlng){
        $this->requestParameters['latlng'] = $latlng;
        return $this;
    }

    /**
     * 
     * @return 
     */
    public function getSensor(){
        return $this->requestParameters['sensor'];
    }

    /**
     * 
     * @param $sensor
     */
    public function setSensor($sensor){
        $this->requestParameters['sensor'] = $sensor;
        return $this;
    }

    /**
     * 
     * @return 
     */
    public function getBounds(){
        return $this->requestParameters['bounds'];
    }

    /**
     * 
     * @param $bounds
     */
    public function setBounds($bounds){
        $this->requestParameters['bounds'] = $bounds;
    }

    /**
     * 
     * @return 
     */
    public function getLanguage(){
        return $this->requestParameters['language'];
    }

    /**
     * 
     * @param $language
     */
    public function setLanguage($language){
        $this->requestParameters['language'] = $language;
    }

    /**
     * 
     * @return 
     */
    public function getRegion(){
        return $this->requestParameters['region'];
    }

    /**
     * 
     * @param $region
     */
    public function setRegion($region){
        $this->requestParameters['region'] = $region;
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
        
        
        return $this->responseFormat === 'xml' ?
            new GoogleGeocoderXmlResponse($this->convertResponse($response)) :
            new GoogleGeocoderJsonResponse($this->convertResponse($response));
        
    }
    
    /**
     * convertResponse
     * 
     * @param string $response
     * @return array|SimpleXML
     */
    protected function convertResponse($response){
        if('json' === $this->responseFormat){
            return json_decode($response,true);
        }
        return simplexml_load_string($response);
    }
    
    
    
}