<?php

namespace Spliced\CommerceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
        
    /**
     * testIndex
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cart');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    
    /**
     * testAdd
     */
    public function testAdd()
    {
        $client = static::createClient();
    
        $crawler = $client->request('POST', '/cart/add');
        
        // should not work with no post data
        $this->assertFalse($client->getResponse()->isSuccessful());
        
        $crawler = $client->request('GET', '/cart/add');
        
        // should not work with no data
        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
