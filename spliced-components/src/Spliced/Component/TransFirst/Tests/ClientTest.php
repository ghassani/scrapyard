<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\TransFirst\Tests;

use Spliced\Component\TransFirst\Client;
use Spliced\Component\TransFirst\Request;
use Spliced\Component\TransFirst\Response;

/**
 * ClientTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * Pass your merchant ID followed by Security Key in your command after the this test file
 * 
 * i.e. phpunit -c frontend src/Spliced/Component/TransFirst/Tests/ClientTest.php MERCHANTID REGKEY
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testClient
     * 
     * Tests for basic functionality like class initialization
     * and parameter setup
     */
    public function testClient()
    {
        $arguments = $this->getArguments();
        
        
        $merchantId = $arguments['merchantId']; 
        $regKey = $arguments['regKey']; 
        $testMode = true;

        $client = new Client($merchantId, $regKey, true);

        $this->assertEquals($client->getMerchantId(),$merchantId);
        $this->assertEquals($client->getRegKey(),$regKey);
        $this->assertEquals($client->isTestMode(),true);
        $this->assertNotNull($client->getEntryPoint());


        $request = $client->createRequest(Client::REQUEST_CHARGE, array());
        $this->assertEquals($client->getEntryPoint(),$request->getUri());
        
        $request = $client->createRequest(Client::REQUEST_VOID, array());
        $this->assertEquals($client->getEntryPoint(Client::REQUEST_VOID),$request->getUri());
        
        $request = $client->createRequest(Client::REQUEST_ACH, array());
        $this->assertEquals($client->getEntryPoint(Client::REQUEST_ACH),$request->getUri());
    }
    
    /**
     * testClientDeclinedTransaction
     * 
     * Makes a fake call and tests a real card which declines.
     * 
     */
    public function testClientDeclinedTransaction()
    {
        $arguments = $this->getArguments();
        
        $merchantId = $arguments['merchantId']; 
        $regKey = $arguments['regKey']; 
        $testMode = true;

        
        $client = new Client($merchantId, $regKey, true);

        $request = $client->createRequest(Client::REQUEST_CHARGE, array());
        
        $request->setCreditCard('4302230125563601')
        ->setAmount('2.00')
        ->setTaxIndicator(0)
        ->setTax('0.00')
        ->setRefId('1234')
        ->setInvoiceNumber('1234')
        ->setExpirationMonth('08')
        ->setExpirationYear('16')
        ->setName('Gassan Idriss')
        ->setAddress('7710 Smugglers Cove')
        ->setZipcode('76016')
        ->setCvv2('709');

        $response = $client->doRequest($request);

        $this->assertEquals($response->isSuccess(), true);

        
        $this->assertEquals($response->isDeclined(), true);
    }

    /**
     * getArguments
     * For Testing With Real Keys
     */
    protected function getArguments()
    {
        global $argv, $argc;

        if (!isset($argv[4])&&!isset($argv[5])) {
            return array(
                'merchantId' => 'XXXX',
                'regKey' => 'XXXXXXXX',
            );
        }

        return array(
            'merchantId' => $argv[4],
            'regKey' => $argv[5],
        );
    }
}
