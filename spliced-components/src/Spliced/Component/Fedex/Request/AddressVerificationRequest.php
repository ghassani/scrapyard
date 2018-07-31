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
 * AddressVerificationRequest
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AddressVerificationRequest extends BaseRequest{

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
        'ServiceId' => 'aval', 
        'Major' => 2, 
        'Intermediate' => 0, 
        'Minor' => 0
     ),
     'RequestTimestamp' => null,
     'Options' => array(        
        'CheckResidentialStatus' => 1,
        'MaximumNumberOfMatches' => 5,
        'StreetAccuracy' => 'LOOSE',
        'DirectionalAccuracy' => 'LOOSE',
        'CompanyNameAccuracy' => 'LOOSE',
        'ConvertToUpperCase' => 1,
        'RecognizeAlternateCityNames' => 1,
        'ReturnParsedElements' => 1
     ),
     'AddressesToValidate' => array(
        
     )
   );
   

    /**
     * addAddressVerificationOption
     * 
     * @param $option
     * @param $value
     * 
     * @return AddressVerificationRequest
     */
   public function addAddressVerificationOption($option, $value){
       $this->requestParams['Options'][$option] = $value;
       return $this;
   }
   
    /**
     * getAddressVerificationOptions
     * 
     * @return array
     */
   public function getAddressVerificationOptions(){
       return $this->requestParams['Options'];
   }
}
