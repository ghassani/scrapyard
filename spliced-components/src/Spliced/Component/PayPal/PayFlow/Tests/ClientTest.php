<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\PayPal\PayFlow\Tests;

use Spliced\Component\PayPal\PayFlow\Client;
use Spliced\Component\PayPal\PayFlow\Request;

/**
 * ClientTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testClient()
    {
        $username     = 'username';
        $password     = 'password';
        $vendor     = 'vendor';
        $partner     = 'partner';
        $testMode     = true;

        $client = new Client($username, $password, $vendor, $partner, $testMode);

        $this->assertEquals($username, $client->getUsername());
        $this->assertEquals($password, $client->getPassword());
        $this->assertEquals($vendor, $client->getVendor());
        $this->assertEquals($partner, $client->getPartner());
        $this->assertEquals($testMode, $client->isTestMode());

        $testUrl = $client->getEntryPoint();

        $client->setTestMode(false);

        $this->assertEquals(false, $client->isTestMode());

        $liveUrl = $client->getEntryPoint();

        $this->assertNotEquals($liveUrl,$testUrl);
    }

    public function testClientApproved()
    {
        $username = '';
        $password = '';
        $vendor = '';
        $partner = '';
        $testMode = true;

        $client = new Client($username, $password, $vendor, $partner, $testMode);

        $request = $client->request(Client::REQUEST_AUTHORIZE);

        $request->setCreditCard('4111111111111111')
          ->setExpirationDate('1215')
          ->setCvv2('123')
          ->setAmount(1.54);

        return $client->doRequest($request);
    }
    /**
     * getArguments
     * For Testing With Real Keys
     */
    protected function getArguments()
    {
        global $argv, $argc;

        if (!isset($argv[4])||!isset($argv[5])||!isset($argv[6])||!isset($argv[7])) {
            return false;
        }

        return array(
            'username' => $argv[4],
            'password' => $argv[5],
            'vendor'   => $argv[6],
            'partner'  => $argv[7],
        );
    }
}
