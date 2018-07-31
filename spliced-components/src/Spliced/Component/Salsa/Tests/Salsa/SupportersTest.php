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
 * SupportersTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SupportersTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Test Create & Delete Supporter
     */
    public function testCreateActions(){
        $client = new Client('youremail@somedomain.tld','password',true);
        
        $request = $client->createRequest('Save');
        
        $request->setObject('supporter')
        ->setParameter('Email','ghassani@gmail.com')
        ->setParameter('Receive_Email','1')
        ->setParameter('First_Name','Ghassan')
        ->setParameter('Last_Name','Idriss')
        ->setParameter('Street','940 W San Marcos Blvd')
        ->setParameter('Street_2','Suite D')
        ->setParameter('City','San Marcos')
        ->setParameter('State','CA')
        ->setParameter('Zip','92078')
        ->setParameter('Country','US');
        
        $response = $client->processRequest($request);
        
        $key = $response->getKey();
        /*
        $deleteRequest = $client->createRequest('Delete')
          ->setObject('supporter')
          ->setKey($key);
        
        $deleteResponse = $client->processRequest($deleteRequest);
        
        print_r($deleteResponse->getResponse());*/
        
    }
}