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
 * Base
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Base implements RequestInterface{
    
    /**
     * getRequestPath
     * 
     * @return string
     */
    public function getRequestPath(){
        return $this->requestPath;
    }
    
    /**
     * getParameters
     */
    public function getParameters(){
        return $this->requestParameters;
    }
    
    /**
     * setParameter
     * 
     * @param string $name
     * @param string $value
     */
    public function setParameter($name, $value){
        $this->requestParameters[$name] = $value;
        return $this;
    }
    
    /**
     * getParameter
     * 
     * @param string $name
     */
    public function getParameter($name){
        return isset($this->requestParameters[$name]) ? $this->requestParameters[$name] : null;
    }
}