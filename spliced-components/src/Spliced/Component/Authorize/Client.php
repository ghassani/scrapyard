<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Authorize;

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
    const REQUEST_VOID             = 'VOID';
    const REQUEST_CREDIT         = 'CREDIT';
    const REQUEST_AUTH_CAPTURE  = 'AUTH_CAPTURE';
    const REQUEST_AUTH_ONLY     = 'AUTH_ONLY';

    const ENTRY_POINT = 'https://secure.authorize.net/gateway/transact.dll';
    
    //const ENTRY_POINT = 'https://test.authorize.net/gateway/transact.dll'; 

    protected $transactionKey = null;
    protected $loginKey       = null;
    protected $testMode       = false;

    /**
     * Constructor
     *
     * @param string $transactionKey
     * @param string $loginKey
     * @param bool   $testMode
     */
    public function __construct($transactionKey, $loginKey, $testMode = false)
    {
        $this->transactionKey = $transactionKey;
        $this->loginKey = $loginKey;
        $this->testMode = $testMode;
        $this->client = new GuzzleClient('', array(GuzzleClient::DISABLE_REDIRECTS => true));
        parent::__construct();
    }

    /**
     * getTransactionKey
     *
     * @return string
     */
    public function getTransactionKey()
    {
        return $this->transactionKey;
    }

    /**
     * getLoginKey
     *
     * @return string
     */
    public function getLoginKey()
    {
        return $this->loginKey;
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
     * @return string
     */
    public function getEntryPoint()
    {
        return self::ENTRY_POINT;
    }

    /**
     * {@inheritDoc}
     */
    public function doRequest($request)
    {
        if (in_array($request->getMethod(), array('GET','HEAD'))) {
            throw new AuthorizeException('Request must be POST');
        }

        if ($this->isTestMode()) {
            $request->setParameter('x_test_request',true);
        }

        $request->setParameter('x_login', $this->getLoginKey())
          ->setParameter('x_tran_key', $this->getTransactionKey());

        $headers = array();
        foreach ($request->getServer() as $key => $val) {
            $key = ucfirst(strtolower(str_replace(array('_', 'HTTP-'), array('-', ''), $key)));
            if (!isset($headers[$key])) {
                $headers[$key] = $val;
            }
        }

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
     * @param  string  $transactionType
     * @param  string  $transactionMethod
     * @param  array   $request
     * @return Request
     */
    public function createRequest($transactionType, $transactionMethod = 'CC', array $request = array(), $execute = false)
    {
        if ($this->isTestMode()) {
            $request = array_merge($request,array('x_test_request' => true));
        }

        $request = new Request($this->getEntryPoint(), $transactionType, $transactionMethod, $request);

        if (true === $execute) {
            return $this->doRequest($request);
        }

        return $request;
    }

}
