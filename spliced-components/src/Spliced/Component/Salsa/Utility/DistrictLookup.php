<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Salsa\Utility;

/**
 * DistrictLookup
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class DistrictLookup{
    
    const URL = 'http://warehouse.salsalabs.com/salsa/api/warehouse/append.jsp';
    
    const COOKIE_PATH = '/tmp/salsa_public';
    
    protected $defaultParameters = array(
        'json' => '',
        'postal_code' => null
    );
    
    protected $parameters = array();
    

    /**
     * lookup
     * 
     * Looks up an activists district by zipcode and address
     * 
     * @param string $zipcode - Can contain Zip4 separated by a -
     * @param string $address - optional but recommended
     */
    public function lookup($zipcode, $address = null){
        $this->parameters = $this->defaultParameters;
        
        if(stripos($zipcode, '-') !== false){
            $zips = explode('-',$zipcode);
            $zipcode = $zips[0];
            if(isset($zips[1])){
                $this->parameters['postal_code_extension'] = $zips[1];
            }    
        }
        
        $this->parameters['address1'] = $address;
        $this->parameters['postal_code'] = $zipcode;

        return $this->processRequest();
    }
        
    /**
     * processRequest
     * 
     * processes the current request
     * 
     * @return array
     */
    protected function processRequest(){
        
        $this->session = curl_init(self::URL.'?'.http_build_query($this->getParameters()));
        curl_setopt($this->session, CURLOPT_HTTPGET, true);
        curl_setopt($this->session, CURLOPT_RETURNTRANSFER, true);
        
        return json_decode(curl_exec($this->session),true);
    }
    

    /**
     * getParameters
     * 
     * The current request parameters
     * 
     * @return array
     * 
     */
    public function getParameters(){
        return $this->parameters;
    }
}