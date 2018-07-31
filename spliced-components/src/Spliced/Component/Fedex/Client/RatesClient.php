<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Fedex\Client;

use Spliced\Component\Fedex\Client;

/**
 * RatesClient
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RatesClient extends Client{

    /**
     * 
     */
    public function createRequest(){
        return clone $this->requestClass;
    }
    
    public function getRequestClass(){
        return 'RatesRequest';
    }
    
    public function getWsdlName(){
        return 'RateService_v10.wsdl';
    }
}
