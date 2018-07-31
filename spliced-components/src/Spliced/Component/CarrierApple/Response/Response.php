<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\Response;


/**
 * Response
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Response implements ResponseInterface
{
    protected $data = array();
    
    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getMsgCode()
    {
        return isset($this->data['msg_code']) ? $this->data['msg_code'] : 0;
    }
    
    /**
     * get
     * 
     * Gets a value by key from the request data, returns specified default if not set
     * 
     * @param string $key
     * @param mixed $default
     * 
     * @return RequestInterface
     */
     public function get($key, $default = null)
     {
         if(isset($this->data[$key])){
             return $this->data[$key];
         }
                 
        return $default;
     }
     
     /**
      * {@inheritDoc}
      */
     public function isSuccess()
     {
         if($this instanceof AccountInformation && $this->getMsgCode() === 0){
             return true;
         }
        
        if($this->getMsgCode() == 5){
            return true;
        }
        
        return false;
     }

     /**
      * {@inheritDoc}
      */
     public function isError()
     {
         return !$this->isSuccess();
     }
     
     /**
      * {@inheritDoc}
      */
     public function getError()
     {
         switch($this->getMsgCode()){
            default:
                return null;
                break;
            case ResponseInterface::RESPONSE_CODE_API_KEY_INVALID:
                return 'API Key Invalid';
                break;
            case ResponseInterface::RESPONSE_CODE_DATA_INVALID:
                return 'Request Data Invalid';
                break;
            case ResponseInterface::RESPONSE_CODE_DEVICE_REPLACED:
                return 'Device Replaced - Use GSX';
                break;
            case ResponseInterface::RESPONSE_CODE_CHECK_LATER:
                return 'Check Back Later';
                break;
            case ResponseInterface::RESPONSE_CODE_INSUFFICIENT_CREDITS:
                return 'Insufficient Credits';
                break;
         }
     }
     
     /**
      * {@inheritDoc}
      */
      public function getResponseData()
      {
          return $this->data;
      }
}