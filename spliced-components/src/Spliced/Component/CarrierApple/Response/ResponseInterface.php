<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\Response;

/**
 * ResponseInterface
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ResponseInterface
{
    /** API RESPONSE CODES */
    const RESPONSE_CODE_ACCOUNT_INFO = 0; // a successful account info response
    const RESPONSE_CODE_API_KEY_INVALID = 1;
    const RESPONSE_CODE_DATA_INVALID = 2;
    const RESPONSE_CODE_DEVICE_REPLACED = 3; // use GSX Check
    const RESPONSE_CODE_CHECK_LATER = 4;
    const RESPONSE_CODE_DONE = 5;
    const RESPONSE_CODE_INSUFFICIENT_CREDITS = 6;
    
    /**
     * getMsgCode
     * 
     * Gets the response msg code from the servers response array
     * 
     * @return int
     */
    public function getMsgCode();    
    
    /**
     * getResponseData
     * 
     * Retrieves the response array
     * 
     * @return array
     */
     public function getResponseData();
     
     /**
      * isSuccess
      * 
      * @return bool
      */
     public function isSuccess();

     /**
      * isError
      * 
      * @return bool
      */
     public function isError();
     
     /**
      * getError
      * 
      * @return string|null
      */
     public function getError();
     
}