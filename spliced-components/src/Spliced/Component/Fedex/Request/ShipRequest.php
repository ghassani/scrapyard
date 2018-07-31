<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Fedex\Request;

use Spliced\Component\Fedex\Request as BaseRequest;

/**
 * ShipRequest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShipRequest extends BaseRequest{

   protected $requestParams = array(
    'WebAuthenticationDetail' => array(
        'UserCredential' => array(
            'Key' => null, 
            //'Password' => null,
        ),
     ),
     'ClientDetail' => array(
        'AccountNumber' => null,
        'MeterNumber' => null,
        'ClientProductId' => null,
        'ClientProductVersion' => null,
     ),
     'TransactionDetail' => array(
        'CustomerTransactionId' => null,
     ),
     'Version' => array(
        'ServiceId' => 'ship', 
        'Major' => self::API_VERSION_MAJOR, 
        'Intermediate' => self::API_VERSION_INTERMEDIATE, 
        'Minor' => self::API_VERSION_MINOR
     ),
     'RequestedShipment' => array(
        
     ),
     'SignatureOptionDetail' => array(
        'OptionType' => self::SIGNATURE_OPTION_SERVICE_DEFAULT,
     )
   );
   
}