<?php

namespace Spliced\CommerceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
        
    /**
     * testIndex
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/catalog');

        $this->assertTrue($client->getResponse()->isSuccessful());
        
        // lets to see that categories are getting rendered
        $categoryCount = $crawler->filter('ul.category-list li')->count();
        
        $this->assertGreaterThan($categoryCount, 0);
    }
    

}
