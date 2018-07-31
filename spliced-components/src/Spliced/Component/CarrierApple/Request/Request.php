<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\CarrierApple\Request;


/**
 * Request
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Request implements RequestInterface
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
     * set
     * 
     * Sets a key and value in the request data
     * 
     * @return RequestInterface
     */
     public function set($key, $value)
     {
         $this->data[$key] = $value;
        return $this;
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
      public function getRequestData()
      {
          return $this->data;
      }
}