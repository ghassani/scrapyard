<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Salsa\Tests\Salsa;

use Spliced\Component\Salsa\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * ClientTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ClientTest extends WebTestCase
{
    /**
     * Test a Successful Authentication Client in TEST Mode
     */
    public function testClient(){
        
        $client = static::createClient(array(
            'environment' => 'test',
            'debug'       => true,
        ));
        

        $salsaClient = new Client('youremail@somedomain.tld','password',true);
        
        $this->assertEquals(true, $salsaClient->authenticate(), 'Authenication TEST Failure');
        
        echo "Authenticated TEST Successfully\n\r";
    }
    
    /**
     * Test a Successful Authentication Client in LIVE Mode
     */
    public function testLiveClient(){
    
        $client = static::createClient(array(
                'environment' => 'test',
                'debug'       => true,
        ));
    
        $salsaClient = new Client('youremail@somedomain.tld','password',true);
        
        $this->assertEquals(true, $salsaClient->authenticate(), 'Authenication LIVE Failure');
    
        echo "Authenticated LIVE Successfully\n\r";
    }

}