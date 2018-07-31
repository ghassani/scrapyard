<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\StatusCheck\Response;

/**
 * CheckCarrier
 * 
 * Wrapper holding the check carrier response
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckCarrier extends Response
{

     /**
      * getResult
      */
      public function getResult()
      {
          return isset($this->data['result']) ? $this->data['result'] : null;
      }
      
      
     /**
      * getAmount
      */
      public function getAmount()
      {
          return isset($this->data['amount']) ? $this->data['amount'] : null;
      }
      
      /**
       * getStatus
       */
       public function getStatus()
       {
           return isset($this->data['status']) ? $this->data['status'] : null;
       }
       
}