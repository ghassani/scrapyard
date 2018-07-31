<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Geocoder;

use Spliced\Component\Geocoder\GeocoderResponseInterface;

/**
 * GoogleGeocoderJsonResponse
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleGeocoderJsonResponse implements GeocoderResponseInterface
{
    protected $response;
    
    public function __construct($response){
        $this->response = $response;
    }
    
    /**
     * getResponse
     * 
     * @return array
     */
    public function getResponse(){
        return $this->response;
    }
    

}