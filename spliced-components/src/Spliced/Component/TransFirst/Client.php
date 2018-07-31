<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\TransFirst;

use Symfony\Component\BrowserKit\Client as BaseClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\CurlException;

/**
 * Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Client extends BaseClient
{
    const REQUEST_CHARGE    = 1;
    const REQUEST_VOID         = 2;
    const REQUEST_ACH          = 3;

    const ENTRY_POINT_CHARGE = 'https://webservices.primerchants.com/billing/TransactionCentral/processCC.asp';
    const ENTRY_POINT_VOID = 'https://webservices.primerchants.com/billing/TransactionCentral/voidcreditcconline.asp';
    const ENTRY_POINT_ACH = 'https://webservices.primerchants.com/billing/TransactionCentral/processcheckonline.asp';
    

    protected $merchantId = null;
    protected $regKey       = null;
    protected $testMode   = false;

    /**
     * Constructor
     *
     * @param string $transactionKey
     * @param string $loginKey
     * @param bool   $testMode
     */
    public function __construct($merchantId, $regKey, $testMode = false)
    {
        $this->merchantId = $merchantId;
        $this->regKey = $regKey;
        $this->testMode = $testMode;
        $this->client = new GuzzleClient('', array(GuzzleClient::DISABLE_REDIRECTS => true));
        parent::__construct();
    }

    /**
     * getMerchantId
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * getRegKey
     *
     * @return string
     */
    public function getRegKey()
    {
        return $this->regKey;
    }

    /**
     * setTestMode
     *
     * @param  bool   $mode
     * @return Client
     */
    public function setTestMode($mode)
    {
        $this->testMode = $mode;

        return $this;
    }

    /**
     * isTestMode
     *
     * @return bool
     */
    public function isTestMode()
    {
        return $this->testMode;
    }

    /**
     * getEntryPoint
     *
     * @param $requestType
     * 
     * @return string
     */
    public function getEntryPoint($requestType = Client::REQUEST_CHARGE)
    {
        if($requestType == static::REQUEST_VOID){
            return static::ENTRY_POINT_VOID;
        } else if($requestType == static::REQUEST_ACH){
            return static::ENTRY_POINT_ACH;
        }
        return static::ENTRY_POINT_CHARGE;
    }

    /**
     * {@inheritDoc}
     */
    public function doRequest($request)
    {
        if (in_array($request->getMethod(), array('GET','HEAD'))) {
            throw new AuthorizeException('Request must be POST');
        }

        $request->setParameter('MerchantID', $this->getMerchantId())
          ->setParameter('RegKey', $this->getRegKey());
        

        $headers = array();

        $body = null;
        if (!in_array($request->getMethod(), array('GET','HEAD'))) {
            if (null !== $request->getContent()) {
                $body = $request->getContent();
            } else {
                $body = $request->getParameters();
            }
        }

        $guzzleRequest = $this->client->createRequest(
            $request->getMethod(),
            $request->getUri(),
            $headers,
            $body
        );
        
        // Let BrowserKit handle redirects
        try {
            $response = $guzzleRequest->send();
        } catch (CurlException $e) {
            if (!strpos($e->getMessage(), 'redirects')) {
                throw $e;
            }

            $response = $e->getResponse();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }
        
        return new Response($response->getBody(true), $response->getStatusCode(), $response->getHeaders()->getAll());
    }

    /**
     * createRequest
     *
     * @param int $requestType - The Request Type. See REQUEST_ constants in this class
     * @param array $request - Request Parameters to Pass/Override
     * @param bool $execute - Creates and Executes Request, returning a Response
     * @return Request|Response
     */
    public function createRequest($requestType, array $request = array(), $execute = false)
    {

        $request = new Request($this->getEntryPoint($requestType), $requestType, $request);

        if (true === $execute) {
            return $this->doRequest($request);
        }

        return $request;
    }

}
