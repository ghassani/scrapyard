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

/**
 * ActionsTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ActionsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Get Actions
     */
    public function testGetActions(){
    
        $client = new Client('youremail@somedomain.tld','password',true);
        
        $request = $client->createRequest('GetObjects');
        
        $request->setObject('action');
        
        $response = $client->processRequest($request);

        $this->assertEquals(true, $response->isSuccess(), $response->getError());
        
        $actions = $response->getObjects();
        
        print sprintf("Recieved %s Actions\n\r", count($actions));
        
        foreach($actions as $key => $action){
            print(sprintf("Key: %s \t Title: %s \t Status: %s \t Created: %s\n\r",
                $action['key'],
                $action['Title'],
                $action['Status'],
                $action['Date_Created']
            ));
        
            $objectRequest = $client->createRequest('GetObject');
            
            $objectRequest->setObject('action')
              ->setKey($action['key']);
            
            $objectResponse = $client->processRequest($objectRequest);
            
            $this->assertEquals(true, $objectResponse->isSuccess(), $objectResponse->getError());
            
            $objectResponseData = $objectResponse->getObject();

            print sprintf("\tIndividual Load Successful For %s\r\n", $objectResponseData['key']);

        }
        
        print sprintf("Made a Total of %s Requests\r\n",count($client->getResponseStack()));
    }
    
    /**
     * Test Create Actions
     */
    public function testCreateActions(){
        $client = new Client('youremail@somedomain.tld','password',true);
        
        $request = $client->createRequest('Save');
        
        $request->setObject('supporter')
        ->setParameter('email','ghassani@gmail.com')
        ->setParameter('Receive_Email','1')
        ->setParameter('First_Name','Ghassan')
        ->setParameter('Last_Name','Idriss');
        
        
    }
}