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
 * AccountInformation
 * 
 * Wrapper holding the account information response
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AccountInformation extends Response
{    

    /**
     * getCredit
     * 
     * @return float
     */
     public function getCredit()
     {
         return isset($this->data['credit']) ? $this->data['credit'] : null;
     }
     
    /**
     * getUsername
     * 
     * @return string
     */
     public function getUsername()
     {
         return isset($this->data['username']) ? $this->data['username'] : null;
     }
     
     
    /**
     * getUsername
     * 
     * @return string
     */
     public function getEmail()
     {
         return isset($this->data['email']) ? $this->data['email'] : null;
     }
     
     
    /**
     * getCreditConsume
     * 
     * @return float
     */
     public function getCreditConsume()
     {
         return isset($this->data['credit_consume']) ? $this->data['credit_consume'] : null;
     }
}