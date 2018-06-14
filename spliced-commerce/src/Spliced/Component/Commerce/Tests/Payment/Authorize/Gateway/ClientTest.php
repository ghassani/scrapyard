<?php

namespace Spliced\Component\Commerce\Tests\Payment\Authorize\Gateway;

use Spliced\Component\Commerce\Payment\Authorize\Gateway\Client;
use Spliced\Component\Commerce\Tests\ContainerAwareUnitTestCase;

class CalculatorTest extends ContainerAwareUnitTestCase
{

    /**
     * testClientInitialization
     */
    public function testClientInitialization()
    {
        $transactionKey = $this->get('commerce.configuration')->get('commerce.payment.authorize.transaction_key');
        $loginKey = $this->get('commerce.configuration')->get('commerce.payment.authorize.login_key');

        $this->assertNotNull($transactionKey, 'Transaction Key Must Be Provided');
        $this->assertNotNull($loginKey, 'Login Key Must Be Provided');

        $client = new Client($transactionKey, $loginKey, true);
        $this->assertInstanceOf('Spliced\Component\Commerce\Payment\Authorize\Gateway\Client', $client);
    }

    /**
     * testApprovedAuthorize
     */
    public function testApprovedAuthorize()
    {
        $client = $this->createClient();

        $authorizeRequest = $client->createRequest('Authorize');

        $this->assertEquals($authorizeRequest->getTransactionType(), 'AUTH_ONLY');
        $this->assertEquals($authorizeRequest->getTransactionMethod(), 'CC');

        $authorizeRequest->setCreditCard('4222222222222')
          ->setAmount(1.00)
          ->setExpirationDate(date('my'));

        $response = $client->processRequest($authorizeRequest);

        $this->assertEquals(true, $response->isSuccess());
        $this->assertNotNull($response->getAuthorizationCode());

        print(PHP_EOL.'testApprovedAuthorize:'.PHP_EOL);
        print('Response Code: '.$response->getErrorCode().PHP_EOL);
        print('Response Message: '.$response->getResponseMessage().PHP_EOL);
        print('Authorization Code: '.$response->getAuthorizationCode().PHP_EOL);
    }

    /**
     * testDeclinedAuthorize
     */
    public function testDeclinedAuthorize()
    {
        $client = $this->createClient();

        $authorizeRequest = $client->createRequest('Authorize');

        $this->assertEquals($authorizeRequest->getTransactionType(), 'AUTH_ONLY');
        $this->assertEquals($authorizeRequest->getTransactionMethod(), 'CC');

        $authorizeRequest->setCreditCard('4222222222222')
          ->setAmount('2.00')
          ->setExpirationDate(date('my'));

        $response = $client->processRequest($authorizeRequest);

        $this->assertEquals(false, $response->isSuccess());

        print(PHP_EOL.'testDeclinedAuthorize:'.PHP_EOL);
        print('Response Code: '.$response->getErrorCode().PHP_EOL);
        print('Response Message: '.$response->getResponseMessage().PHP_EOL);
        print('Authorization Code: '.$response->getAuthorizationCode().PHP_EOL);
    }

    /**
     * testApprovedAuthorizeAndCapture
     */
    public function testApprovedAuthorizeAndCapture()
    {
        $client = $this->createClient();

        $authorizeAndCaptureRequest = $client->createRequest('AuthorizeAndCapture');

        $this->assertEquals($authorizeAndCaptureRequest->getTransactionType(), 'AUTH_CAPTURE');
        $this->assertEquals($authorizeAndCaptureRequest->getTransactionMethod(), 'CC');

        $authorizeAndCaptureRequest->setCreditCard('4222222222222')
        ->setAmount(1.00)
        ->setExpirationDate(date('my'));

        $response = $client->processRequest($authorizeAndCaptureRequest);

        $this->assertEquals(true, $response->isSuccess());
        $this->assertNotNull($response->getAuthorizationCode());

        print(PHP_EOL.'testApprovedAuthorizeAndCapture:'.PHP_EOL);
        print('Response Code: '.$response->getErrorCode().PHP_EOL);
        print('Response Message: '.$response->getResponseMessage().PHP_EOL);
        print('Authorization Code: '.$response->getAuthorizationCode().PHP_EOL);
    }

    /**
     * testDeclinedAuthorizeAndCapture
     */
    public function testDeclinedAuthorizeAndCapture()
    {
        $client = $this->createClient();

        $authorizeAndCaptureRequest = $client->createRequest('AuthorizeAndCapture');

        $this->assertEquals($authorizeAndCaptureRequest->getTransactionType(), 'AUTH_CAPTURE');
        $this->assertEquals($authorizeAndCaptureRequest->getTransactionMethod(), 'CC');

        $authorizeAndCaptureRequest->setCreditCard('4222222222222')
        ->setAmount('2.00')
        ->setExpirationDate(date('my'));

        $response = $client->processRequest($authorizeAndCaptureRequest);

        $this->assertEquals(false, $response->isSuccess());

        print(PHP_EOL.'testDeclinedAuthorizeAndCapture:'.PHP_EOL);
        print('Response Code: '.$response->getErrorCode().PHP_EOL);
        print('Response Message: '.$response->getResponseMessage().PHP_EOL);
        print('Authorization Code: '.$response->getAuthorizationCode().PHP_EOL);
    }
    /**
     * createClient
     *
     * @param  bool   $testMode
     * @return Client
     */
    private function createClient($testMode = true)
    {
        return new Client(
            $this->get('commerce.configuration')->get('commerce.payment.authorize.transaction_key'),
            $this->get('commerce.configuration')->get('commerce.payment.authorize.login_key'),
            $testMode
        );
    }
}
