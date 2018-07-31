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
 * AccountInformation
 * 
 * Retrieves account information from carrierappl.com such as credits and profile
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AccountInformation extends Request
{
    /**
     * {@inheritDoc}
     */
    public function getCallMethod()
    {
        return 'account-info';
    }
    
    /**
     * getRequestData
     * 
     * Overrides abstract class method to return no parameters as none are needed for this request
     * besides the api key, which is always required and handled in the client doRequest() method
     * 
     * @return array
     */
     public function getRequestData()
     {
         return array();
     }
}