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
    public function testValidZipcode(){
        
        $geocoder = new SunlightDisctrictGeocoder('9e0ad0272f12487eb0a59222f5a17295');
        
        $testZipcode = '92024';
        
        print sprintf('Testing Zipcode: %s ',$testZipcode)."\n";
        
        $geocoder->setZipcode($testZipcode);
        
        $results = $geocoder->sendRequest();

        $this->assertEquals(true, $results->isSuccess());

        $this->assertGreaterThanOrEqual(1, $results->getDistricts());
        
        foreach( $results->getDistricts() as $result ){
            print $result['number']."\n\r";
        }
    }

}
