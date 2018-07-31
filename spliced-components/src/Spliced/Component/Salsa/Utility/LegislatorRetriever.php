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
 * LegislatorRetriever
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LegislatorRetriever {
    
    const URL = 'http://hq-org2.salsalabs.com/dia/api/warehouse/pipe.jsp';
    //http://hq-org2.democracyinaction.org/dia/api/warehouse/pipe.jsp?url=legislator.jsp%3Fformat%3Dxml%26method%3DgetLegislatorNames%26district_type%3DFH
    const COOKIE_PATH = '/tmp/salsa_public';
    
    protected $defaultParameters = array(
        'url' => '',
    );
    
    protected $parameters = array();
    

    /**
     * __construct
     * @param int $zip5
     * @param int $zip4
     */
    public function loadByDistrictType($districtType){
        $this->parameters = $this->defaultParameters;
        
        $this->parameters['url'] = urlencode('legislator.jsp?'.http_build_query(array(
            'format' => 'xml',
            'method' => 'getLegislatorNames',
            'district_type' => $districtType
        )));

        return $this->processRequest();
    }
        
    /**
     * processRequest
     * 
     * @return array
     */
    protected function processRequest(){
    
        $this->session = curl_init(self::URL.'?url='.$this->parameters['url']);
        curl_setopt($this->session, CURLOPT_HTTPGET, true);
        curl_setopt($this->session, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($this->session);
        curl_close($this->session);
        return simplexml_load_string($response);
    }
    

    
    public function getParameters(){
        return $this->parameters;
    }
}