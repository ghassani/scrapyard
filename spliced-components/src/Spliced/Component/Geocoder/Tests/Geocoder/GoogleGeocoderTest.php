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

use Spliced\Component\Geocoder\GoogleGeocoder;

/**
 * GoogleGeocoderTest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleGeocoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test with JSON Response
     */
    public function testValidAddressJson(){
        
        $geocoder = new GoogleGeocoder('json');
        
        $testAddress = '291 Fairlee Ln Encinitas CA';
        
        print sprintf('Testing Address: %s - JSON',$testAddress)."\n";
        
        $geocoder->setAddress($testAddress);
        
        $results = $geocoder->sendRequest();
        
        $this->assertEquals('OK', $results['status']);
        
        $this->assertGreaterThanOrEqual(1, count($results['results']));
        
        foreach( $results['results'] as $result ){
            print sprintf('Formatted: %s',$result['formatted_address'])."\n";
        }
        
    }
    
    /**
     * Test with XML Response
     */
    public function testValidAddressXml(){
    
        $geocoder = new GoogleGeocoder('xml');
    
        $testAddress = '291 Fairlee Ln Encinitas CA';
    
        print sprintf('Testing Address: %s - XML',$testAddress)."\n";
    
        $geocoder->setAddress($testAddress);
    
        $results = $geocoder->sendRequest();

        $this->assertEquals('OK', $results->status);

        print sprintf('Formatted: %s',$results->result->formatted_address)."\n";
    
    }
}