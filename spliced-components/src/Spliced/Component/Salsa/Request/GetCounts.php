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
 * GetCounts
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GetCounts extends Base{
    
    protected $requestPath = 'api/getCounts.sjs';
    
    protected $requestParameters = array(
        'object' => null,
    );
    
    /**
     * isValid
     * 
     * @return bool
     */
    public function isValid(){
        return $this->getObject() ? true : false;
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
     * setCondition
     *
     * @param string $value
     */
    public function setCondition($value){
        return $this->setParameter('condition',$value);
    }
    
    /**
     * getCondition
     *
     * @return string
     */
    public function getCondition(){
        return $this->getParameter('condition');
    }
    
    /**
     * setCountColumn
     *
     * @param string $value
     */
    public function setCountColumn($value){
        return $this->setParameter('countColumn',$value);
    }
    
    /**
     * getCountColumn
     *
     * @return string
     */
    public function getCountColumn(){
        return $this->getParameter('countColumn');
    }
    
    
}