<?php
/**
 * Created by PhpStorm.
 * User: Gassan
 * Date: 8/18/14
 * Time: 9:51 PM
 */

/**
 * ?auth-id=5f9aab88-32bd-474a-ace3-4f6854b22218&auth-token=hwsLOqirpDf7uX03NPzJ&
 * street=1600%20Amphitheatre%20Parkway&
 * street2=&
 * city=Mountain%20View&
 * state=CA&
 * zipcode=&
 * candidates=10
 */
namespace Spliced\Component\Geocoder;

use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client as GuzzleHttpClient;

class SmartyStreetsGeocoder implements GeocoderInterface
{
    const API_URL = 'https://api.smartystreets.com/street-address';

    protected $client;

    protected $authId;

    protected $authToken;

    protected $requestParameters = array(
        'auth-id' => null,
        'auth-token' => null,
        'street' => null,
        'street2' => null,
        'city' => null,
        'state' => null,
        'zipcode' => null,
        'candidates' => 10,
    );

    public function __construct($authId, $authToken)
    {
        $this->authId = $authId;
        $this->authToken = $authToken;

        $this->client = new GuzzleHttpClient();
    }



    public function getUrl()
    {
        return static::API_URL;
    }

    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    public function getAddress()
    {
        return $this->requestParameters['street'];
    }

    public function setAddress($address)
    {
        $this->requestParameters['street'] = $address;
        return $this;
    }

    public function getAddress2()
    {
        return $this->requestParameters['street2'];
    }

    public function setAddress2($address)
    {
        $this->requestParameters['street2'] = $address;
        return $this;
    }
    public function getCity()
    {
        return $this->requestParameters['city'];
    }

    public function setCity($city)
    {
        $this->requestParameters['city'] = $city;
        return $this;
    }
    public function getState()
    {
        return $this->requestParameters['state'];
    }

    public function setState($state)
    {
        $this->requestParameters['state'] = $state;
        return $this;
    }
    public function getZipcode()
    {
        return $this->requestParameters['zipcode'];
    }

    public function setZipcode($zipcode)
    {
        $this->requestParameters['zipcode'] = $zipcode;
        return $this;
    }

    public function getCandidates()
    {
        return $this->requestParameters['candidates'];
    }

    public function setCandidates($candidates)
    {
        $this->requestParameters['candidates'] = (int) $candidates;
        return $this;        
    }
    
    /**
     *
     */
    public function sendRequest(){

        $url = sprintf('%s?auth-id=%s&auth-token=%s&%s',
            $this->getUrl(),
            $this->authId,
            $this->authToken,
            http_build_query($this->requestParameters, null, '&')
        );

        try{
            $response = $this->client->get($url);
        } catch(\GuzzleHttp\Exception\ClientException $e){
            $response = $e->getResponse();
        }
        return new SmartyStreetsGeocoderResponse($response);
    }
}