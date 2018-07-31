<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\StatusCheck;

use Spliced\Component\CarrierApple\StatusCheck\Request;
use Spliced\Component\CarrierApple\StatusCheck\Response;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\CurlException;

/**
 * Client
 * 
 * Client interface to make requests to carrierappl.com API Interface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Client
{
    
    const ENDPOINT = 'http://carrierapple.com/api';
    
    protected $apiKey;
    
    /**
     * Constructor
     * 
     * @param string $apiKey
     * 
     * @return Client
     */
    public function __construct($apiKey)
    {
        $this->client = new GuzzleClient('', array(GuzzleClient::DISABLE_REDIRECTS => true));
        $this->apiKey = $apiKey;
    }
    
    /**
     * getApiKey
     * 
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
    
    /**
     * setApiKey
     * 
     * @param string $apiKey
     * 
     * @return Client
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }
    
    /**
     * getAccountInformation
     */
     public function getAccountInformation()
     {

        return new Response\AccountInformation($this->doRequest(new Request\AccountInformation()));
     }
     
    /**
     * getCarrierInformation
     */
     public function getCarrierInformation($meid)
     {

        return new Response\CheckCarrier($this->doRequest(new Request\CheckCarrier($meid)));
     }
     
    /**
     * getCarrierInformation
     */
     public function getSimLockInformation($meid)
     {

        return new Response\CheckSimlock($this->doRequest(new Request\CheckSimlock($meid)));
     }
     
    /**
     * doRequest
     * 
     * Processes a Request object and returns a Response object
     * 
     * @return ResponseInterface
     */
     public function doRequest(Request\RequestInterface $request)
     {
         $requestData = array_merge($request->getRequestData(), array('format' => 'json', 'key' => urlencode($this->getApiKey())));
        
        $apiRequest = $this->client->createRequest(
            'GET',
            static::ENDPOINT.'/'.$request->getCallMethod().'?'.http_build_query($requestData),
            array(),
            null
        );
        
        $apiResponse = $apiRequest->send();

        return json_decode($apiResponse->getBody(true), true);
     }
}
