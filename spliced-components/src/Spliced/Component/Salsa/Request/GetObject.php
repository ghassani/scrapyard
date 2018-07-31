<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Salsa\Request;

/**
 * GetObject
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GetObject extends Base{
    
    /*
     * {@inheri_doc}
     */
    protected $requestPath = 'api/getObject.sjs';
    
    /*
     * {@inheri_doc}
    */
    protected $requestParameters = array(
        'json' => '',
        'object' => null,
        'key' => null,
    );
    
    /**
     * isValid
     * 
     * @return bool
     */
    public function isValid(){
        return $this->getObject() && $this->getKey() ? true : false;
    }
    
    /**
     * getObject
     *
     * @return string
     */
    public function getObject(){
        return $this->getParameter('object');
    }
    
    /**
     * setObject
     * 
     * @param string $value
     */
    public function setObject($value){
        return $this->setParameter('object',$value);
    }
    
    
    /**
     * getKey
     *
     * @return string
     */
    public function getKey(){
        return $this->getParameter('key');
    }
    
    /**
     * setKey
     *
     * @param string $value
     */
    public function setKey($value){
        return $this->setParameter('key',$value);
    }
}