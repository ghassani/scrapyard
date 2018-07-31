<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Geocoder\Tests\Geocoder;

use Spliced\Component\Geocoder\YahooGeocoder;

/**
 * YahooGeocoderTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class YahooGeocoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test with Serialized PHP Response
     */
    public function testValidAddressJson(){
        
        $geocoder = new YahooGeocoder('json');
        
        $testAddress = '291 Fairlee Ln Encinitas CA';
        
        print sprintf('Testing Address: %s - JSON',$testAddress)."\n";
        
        $geocoder->setLocation($testAddress);
        
        $results = $geocoder->sendRequest();

        $this->assertEquals(true, $results->isSuccess());

        $this->assertGreaterThanOrEqual(1, $results->getFound());
        
        foreach( $results->getResults() as $result ){
            print $result->getFullAddress()."\n\r";
        }
    }
    
    /**
     * Test with Serialized PHP Response
     */
    public function testInValidAddressJson(){
    
        $geocoder = new YahooGeocoder('json');
    
        $testAddress = '91 Fairlee Ln Encinitas CA';
    
        print sprintf('Testing Address: %s - JSON',$testAddress)."\n";
    
        $geocoder->setLocation($testAddress);
    
        $results = $geocoder->sendRequest();
    
        $this->assertEquals(true, $results->isSuccess());
    
        $this->assertGreaterThanOrEqual(1, $results->getFound());
    
        foreach( $results->getResults() as $result ){
            print $result->getFullAddress()."\n\r";
        }
    }
    
    /**
     * Test with Serialized PHP Response
     */
    public function testValidAddressWithSuiteJson(){
    
        $geocoder = new YahooGeocoder('json');
    
        $testAddress = '94 W San Marcos Blvd Suite D San Marcos CA';
    
        print sprintf('Testing Address: %s - JSON',$testAddress)."\n";
    
        $geocoder->setLocation($testAddress);
    
        $results = $geocoder->sendRequest();
    
        $this->assertEquals(true, $results->isSuccess());
    
        $this->assertGreaterThanOrEqual(1, $results->getFound());
    
        foreach( $results->getResults() as $result ){
            print $result->getFullAddress()."\n\r";
        }
    }
}
