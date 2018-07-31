<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\Request;

/**
 * RequestInterface
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface RequestInterface
{
    /**
     * getCallMethod
     * 
     * Retrieves the request object call method to append to endpoint URL
     * 
     * @return string
     */
    public function getCallMethod();
    
    /**
     * getRequestData
     * 
     * Retrieves the request array to send with the request
     * 
     * @return array
     */
     public function getRequestData();
}