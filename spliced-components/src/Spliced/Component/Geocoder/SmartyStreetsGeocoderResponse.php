<?php

namespace Spliced\Component\Geocoder;

use Spliced\Component\Geocoder\GeocoderResponseInterface;

/**
 * Class SmartyStreetsGeocoderResponse
 * @package Spliced\Component\Geocoder
 */
use GuzzleHttp\Message\Response;

class SmartyStreetsGeocoderResponse implements GeocoderResponseInterface
{
    /**
     * @var array|mixed
     */
    protected $response = array(),
              $responseObject = null;

    /**
     * @param $response
     */
    public function __construct(Response $response){
        $this->responseObject = $response;
        $this->response = json_decode($response->getBody(true), true);
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Response
     */
    public function getResponseObject()
    {
        return $this->responseObject;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        $return = array();
        foreach($this->response as $address){
            $return[] = new SmartyStreetsAddress($address);
        }
        return $return;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return 200 == $this->responseObject-> getStatusCode();
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return 200 != $this->responseObject-> getStatusCode();
    }

    /**
     * @return string
     */
    public function getError()
    {
        switch($this->responseObject->getStatusCode()){
            case 200:
                return 'No Errors In Response';
                break;
            case 400:
                return 'Bad input. Required fields missing from input or are malformed.';
                break;
            case 401:
                return 'Authentication failure. Bad Credentials';
                break;
            case 402:
                return 'Payment Required. No Active Subscription Found';
                break;
            case 500:
                return 'Internal Server Error. Please Retry Your Request.';
                break;
            default:
                return 'Unknown Error';
                break;
        }
    }
}