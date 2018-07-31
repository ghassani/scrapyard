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

/**
 * GeocoderResponseInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface GeocoderResponseInterface{
    
    public function __construct($response);
        
    public function getResponse();
    
    public function isSuccess();
    
    public function isError();
    
    public function getError();
    
}