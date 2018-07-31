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
 * Delete
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Delete extends Base{
    
    public function getObject(){
        return $this->getResponse();
    }
    
    /**
     * isSuccess
     */
    public function isSuccess(){
        /**
         * As of 10/05/12 Response for Delete shows HTML Partial
         * Assume it was successful.
         */
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
        return null;
    }
}
    