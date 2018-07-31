<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Salsa\Response;

/**
 * Authenticate
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Authenticate extends Base{
    
    /**
     * isSuccess
     */
    public function isSuccess(){
        return $this->response['status'] == 'success' ? true : false;
    }
    
    /**
     * isError
     */
    public function isError(){
        return !$this->isSuccess();
    }
    
    /**
     * getError
     */
    public function getError(){
        return isset($this->response['message']) ? 
          $this->response['message'] : null;
    }
}
    