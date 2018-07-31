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
 * ShipClient
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShipClient extends Client{

	/**
	 * (non-PHPdoc)
	 * @see Spliced\Component\Fedex.Client::createRequest()
	 */
    public function createRequest(){
        return clone $this->requestClass;
    }
    
    /**
     * (non-PHPdoc)
     * @see Spliced\Component\Fedex.Client::getRequestClass()
     */
    public function getRequestClass(){
        return 'ShipRequest';
    }
    
    /**
     * (non-PHPdoc)
     * @see Spliced\Component\Fedex.Client::getWsdlName()
     */
    public function getWsdlName(){
        return 'ShipService_v10.wsdl';
    }
}
