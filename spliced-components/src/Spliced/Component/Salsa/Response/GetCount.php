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
 * GetCount
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GetCount extends Base{
    
    
    public function getObject(){
        if($this->isError()){
            return;
        }
        return $this->response;
    }
    
    /**
     * isSuccess
     */
    public function isSuccess(){
        if(isset($this->response[0]['result']) 
            && $this->response[0]['result'] === 'error'){
            return false;
        }
        return true;
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
        $object = $this->request->getObject();
        return isset($this->response[0]['messages']) ? 
          implode(',',$this->response[0]['messages']) : null;
    }
}
    