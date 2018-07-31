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

use Spliced\Component\Salsa\Request\RequestInterface;

/**
 * Base
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Base implements ResponseInterface{
    
    /**
     * __construct
     * 
     * @param string $rawResponse
     */
    public function __construct(RequestInterface $request, $rawResponse){
        $this->rawResponse = $rawResponse;    
        $this->response = stripos($rawResponse,'<?xml') !== false ? simplexml_load_string($rawResponse) : json_decode($rawResponse,true);
        $this->request = $request;
    }
    
    /**
     * getResponse
     * 
     * @return SimpleXML
     */
    public function getResponse(){
        return $this->response;
    }
    
    /**
     * getRawResponse
     * @return string
     */
    public function getRawResponse(){
        return $this->rawResponse;
    }
    
}
    