<?php

namespace Spliced\Lodestone;

use Spliced\Lodestone\Request\RequestInterface;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use GuzzleHttp\Client as GuzzleClient;
use Spliced\Lodestone\DomCrawler\Crawler;

class Client
{

    const BASE_URL_NA = 'http://na.finalfantasyxiv.com/lodestone';
    const BASE_URL_EU = 'http://eu.finalfantasyxiv.com/lodestone';
    const BASE_URL_JP = 'http://jp.finalfantasyxiv.com/lodestone';
    const BASE_URL_FR = 'http://fr.finalfantasyxiv.com/lodestone';
    const BASE_URL_DE = 'http://de.finalfantasyxiv.com/lodestone';

    protected $lastRequest = null;

    protected $lastResponse = null;

    protected $baseUrl = self::BASE_URL_NA;

    public function __construct($baseUrl = self::BASE_URL_NA, $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0')
    {
        $this->baseUrl = empty($baseUrl) ? static::BASE_URL_NA : $baseUrl;

        $this->guzzle = new GuzzleClient(array(
            'base_url' => $this->baseUrl
        ));

        $this->guzzle->getDefaultUserAgent($userAgent);
    }


    public function send(RequestInterface $request)
    {
        $httpRequest = $this->guzzle->createRequest($request->getMethod(), $this->guzzle->getBaseUrl().$request->getUri());

        $request->buildRequest($httpRequest);

        $httpResponse = $this->guzzle->send($httpRequest);

        $response = $request->buildResponse(new Crawler($httpResponse->getBody()->__toString()));

        $this->setLastRequest($request);
        $this->setLastResponse($response);

        return $response;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }


    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param null $lastRequest
     */
    public function setLastRequest($lastRequest)
    {
        $this->lastRequest = $lastRequest;
        return $this;
    }

    /**
     * @return null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @param null $lastResponse
     */
    public function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
        return $this;
    }

    /**
     * @return null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }



}