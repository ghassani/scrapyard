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
 * Loader
 * 
 * Main entry point for loading an API to make a request 
 * and retrieve a response
 * 
 * <code>
 *   $ratesApi = $loader->getApi('Rates');
 * </code>
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Loader
{
    
    /** @var array */
    protected $apis = array(
        'Ship' => null,
        'Rates' => null,
        'AddressVerification' => null,
    );
    
    /** @var array $parameters */
    protected $parameters = array(
        'authKey' => null,
        'authPassword' => null,
        'accountNumber' => null,
        'meterNumber' => null,
        'clientProductId' => null,
        'clientProductId' => null,
        'clientProductVersion' => null,
    );
    
    /**
     * Constructor
     * 
     * @param array $parameters - All authentication paremeters to connect to Fedex API
     * @see self::$parameters
     */
    public function __construct($parameters){
        
        foreach (array_keys($this->parameters) as $requiredParameter) {
            
            if (!isset($parameters[$requiredParameter])) {
                throw new \InvalidArgumentException(sprintf('Loader requires parameter %s', $requiredParameter));
            }
        }
                
        $this->parameters = array_merge($this->parameters, $parameters);
        
        foreach(array_keys($this->apis) as $api){
            $className = 'Client\\'.$api.'Client';
            if(!class_exists($className)){
                throw new \RuntimeException(sprintf('Class %s Does Not Exist',$className));
            }
            $this->apis[$api] = new $className($parameters, array('trace' => 1));
        }
    }
    
    /**
     * getApi
     * 
     * @param string $apiName - Valid API from self::$apis key
     * 
     * <code>
     *   $ratesApi = $loader->getApi('Rates');
     * </code>
     * 
     * @return Client
     */
    public function getApi($apiName){
        if(isset($this->apis[$apiName])){
            return $this->apis[$apiName];
        }
    }
    
    /**
     * getRatesApi
     * 
     * @return Client
     */
    public function getRatesApi()
    {
        return $this->getApi('Rates');
    }
    
    /**
     * getShipApi
     *
     * @return Client
     */
    public function getShipApi()
    {
        return $this->getApi('Ship');
    }
    
    /**
     * getAddressVerificationApi
     *
     * @return Client
     */
    public function getAddressVerificationApi()
    {
        return $this->getApi('AddressVerification');
    }
    
}
