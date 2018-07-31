<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Fedex;

/**
 * Client
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Client extends \SoapClient{
    
    /** @var string $requestClass */
    protected $requestClass;
    
    /**
     * Constructor
     * 
     * @param array $parameters - Client parameters
     * @param array $soapParameters - Soap Client Parameters
     * 
     * @return self
     */
    public function __construct(array $parameters, array $soapParameters = array()){

        $this->wsdlPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'wsdl'.DIRECTORY_SEPARATOR.self::$this->getWsdlName();
        
        ini_set("soap.wsdl_cache_enabled", "0");

        parent::__construct($this->wsdlPath, $soapParameters);
        
        $requestClassName = 'Request\\'.$this->getRequestClass();
        if(!class_exists($requestClassName)){
            throw new \RuntimeException(sprintf('Class %s Does Not Exist',$requestClassName));
        }
        $this->requestClass = new $requestClassName($parameters);
    }

    
    /**
     * getRequestClass
     * 
     * @return string
     */
    abstract public function getRequestClass();
    
    /**
     * createRequest
     * 
     * Create a request from the available request types.
     */
    abstract public function createRequest();
    
    /**
     * getWsdlName
     * 
     * @return string
     */
    abstract public function getWsdlName();
}
