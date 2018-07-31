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
 * GetObjects
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GetObjects extends Base{
    
    protected $requestPath = 'api/getObjects.sjs';
    
    protected $requestParameters = array(
        'json' => '',
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
     * setOrderBy
     *
     * @param string $value
     */
    public function setOrderBy($value){
        return $this->setParameter('orderBy',$value);
    }
    
    /**
     * getOrderBy
     *
     * @return string
     */
    public function getOrderBy(){
        return $this->getParameter('orderBy');
    }
    
    /**
     * setLimit
     *
     * @param string $value
     */
    public function setLimit($value){
        return $this->setParameter('limit',$value);
    }
    
    /**
     * getLimit
     *
     * @return string
     */
    public function getLimit(){
        return $this->getParameter('limit');
    }
    
    /**
     * setInclude
     *
     * @param string $value
     */
    public function setInclude($value){
        return $this->setParameter('include',$value);
    }
    
    /**
     * getInclude
     *
     * @return string
     */
    public function getInclude(){
        return $this->getParameter('include');
    }
}