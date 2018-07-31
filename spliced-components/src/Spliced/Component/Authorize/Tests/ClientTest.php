<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Authorize\Tests;

use Spliced\Component\Authorize\Client;
use Spliced\Component\Authorize\Request;
use Spliced\Component\Authorize\Response;

/**
 * ClientTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testClient()
    {
        $transactionKey = 'XXX';
        $loginKey = 'XXXX';
        $testMode = true;

        $client = new Client($transactionKey, $loginKey, true);

        $this->assertEquals($client->getTransactionKey(),$transactionKey);
        $this->assertEquals($client->getLoginKey(),$loginKey);
        $this->assertEquals($client->getEntryPoint(),Client::ENTRY_POINT);
        $this->assertEquals($client->isTestMode(),true);
        $this->assertNotNull($client->getEntryPoint());

        $request = $client->request(Client::REQUEST_AUTH_ONLY);

        $this->assertEquals($client->getEntryPoint(),$request->getUri());
    }

    /**
     *
     */
    public function testClientException()
    {
        $transactionKey = 'XXX';
        $loginKey = 'XXXX';
        $testMode = true;

        $client = new Client($transactionKey, $loginKey, true);

        $request = $client->request(Client::REQUEST_AUTH_ONLY);

        $request->setCreditCard('4222222222222')
        ->setAmount(1.00)
        ->setExpirationDate(date('my'));

        try {
            $response = $client->doRequest($request);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Spliced\Component\Authorize\AuthorizeException', $e);
        }
    }

    /**
     *
     */     
    public function testClientApproved()
    {
        $arguments = $this->getArguments();
        if ($arguments == false) {
            $this->assertEquals(true,false);

            return;
        }

        $transactionKey = $arguments['transactionKey'];
        $loginKey = $arguments['loginKey'];
        $testMode = true;

        $client = new Client($transactionKey, $loginKey, $testMode);

        $request = $client->request(Client::REQUEST_AUTH_ONLY);
        $request->setCreditCard('4222222222222')
        ->setAmount(1.00)
        ->setExpirationDate(date('my'));

        $response = $client->doRequest($request);

        $this->assertEquals(true,$response->isSuccess());
    }

    /**
     *
     
    public function testClientDeclined()
    {
        $arguments = $this->getArguments();
        if ($arguments == false) {
            $this->assertEquals(true,false);

            return;
        }

        $transactionKey = $arguments['transactionKey'];
        $loginKey = $arguments['loginKey'];
        $testMode = true;

        $client = new Client($transactionKey, $loginKey, $testMode);

        $request = $client->request(Client::REQUEST_AUTH_ONLY);
        $request->setCreditCard('4222222222222')
        ->setAmount(2.00)
        ->setExpirationDate(date('my'));

        $response = $client->doRequest($request);

        $this->assertEquals(true,$response->isDeclined());
    }
*/
    /**
     * getArguments
     * For Testing With Real Keys
     */
    protected function getArguments()
    {
        global $argv, $argc;

        if (!isset($argv[4])&&!isset($argv[5])) {
            return false;
        }

        return array(
            'transactionKey' => $argv[4],
            'loginKey' => $argv[5],
        );
    }
}
