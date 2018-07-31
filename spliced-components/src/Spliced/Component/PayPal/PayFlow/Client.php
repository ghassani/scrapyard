<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\PayPal\PayFlow;

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
    const ENTRY_POINT         = 'https://payflowpro.paypal.com';
    const ENTRY_POINT_TEST  = 'https://pilot-payflowpro.paypal.com';

    const REQUEST_AUTHORIZE = 'A';
    const REQUEST_CAPTURE   = 'D';
    const REQUEST_CREDIT    = 'C';
    const REQUEST_SALE        = 'S';
    const REQUEST_VOID        = 'V';

    const TENDER_TYPE_CREDIT = 'C';

    protected $username;

    protected $password;

    protected $vendor;

    protected $partner;

    protected $testMode = true;

    /**
     * @param string $username
     * @param string $password
     * @param string $vendor
     * @param string $partner
     * @param bool   $testMode
     */
    public function __construct($username, $password, $vendor, $partner, $testMode = true)
    {
        $this->username = $username;
        $this->password = $password;
        $this->vendor     = $vendor;
        $this->partner     = $partner;
        $this->client     = new GuzzleClient('', array(GuzzleClient::DISABLE_REDIRECTS => true));
        parent::__construct();
    }

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * getPartner
     *
     * @return string
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * getVendor
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
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
     * getEntryPoint
     *
     * @return string
     */
    public function getEntryPoint()
    {
        if (true === $this->isTestMode()) {
            return self::ENTRY_POINT_TEST;
        }

        return self::ENTRY_POINT;
    }

    /**
     * {@inheritDoc}
     */
    public function doRequest($request)
    {
        if (in_array($request->getMethod(), array('GET','HEAD'))) {
            throw new PayflowException('Request must be POST');
        }

        $headers = array();
        foreach ($request->getServer() as $key => $val) {
            $key = ucfirst(strtolower(str_replace(array('_', 'HTTP-'), array('-', ''), $key)));
            if (!isset($headers[$key])) {
                $headers[$key] = $val;
            }
        }

        $request->setParameter('VENDOR', $this->getVendor())
          ->setParameter('PWD', $this->getPassword())
          ->setParameter('USER', $this->getUsername())
          ->setParameter('PARTNER', $this->getPartner());

        $body = null !== $request->getContent() ? $request->getContent() : $request->getParameters();

        $guzzleRequest = $this->client->createRequest(
            $request->getMethod(),
            $request->getUri(),
            $headers,
            $body
        );

        $guzzleRequest->setHeader('Host:', $this->getEntryPoint())
         ->setHeader('Content-Type', 'text/namevalue')
         ->setHeader('X-VPS-CLIENT-TIMEOUT', 45)
         ->setHeader('X-VPS-REQUEST-ID', date('ymd-H').rand(1000,9999));

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
        die($response->getBody(true));

        return new Response($response->getBody(true), $response->getStatusCode(), $response->getHeaders()->getAll());
    }

    /**
     * request
     *
     * @param  string  $transactionType
     * @param  string  $transactionMethod
     * @param  array   $request
     * @return Request
     */
    public function request($transactionType, $tenderType = self::TENDER_TYPE_CREDIT, array $request = array(), $execute = false)
    {
        $request = new Request($this->getEntryPoint(), $transactionType, $tenderType, $request);

        if (true === $execute) {
            return $this->doRequest($request);
        }

        return $request;
    }

}
