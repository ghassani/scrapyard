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
 * Request
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class Request{
   
    const API_VERSION_MAJOR         = 10;
    const API_VERSION_INTERMEDIATE  = 0;
    const API_VERSION_MINOR         = 0;
    
    /* DROPOFF TYPES */
    const DROPOFF_TYPE_REGULAR                  = 'REGULAR_PICKUP';
    const DROPOFF_TYPE_REQUEST_COURIER          = 'REQUEST_COURIER';
    const DROPOFF_TYPE_DROP_BOX                 = 'DROP_BOX';
    const DROPOFF_TYPE_BUSINESS_SERVICE_CENTER  = 'BUSINESS_SERVICE_CENTER';
    const DROPOFF_TYPE_STATION                  = 'STATION';

    /* SERVICE TYPES */
    const SERVICE_TYPE_STANDARD_OVERNIGHT       = 'STANDARD_OVERNIGHT';
    const SERVICE_TYPE_PRIORITY_OVERNIGHT       = 'PRIORITY_OVERNIGHT';
    const SERVICE_TYPE_GROUND                   = 'FEDEX_GROUND';
	const SERVICE_TYPE_GROUND_HOME_DELIVERY		= 'GROUND_HOME_DELIVERY';
    const SERVICE_TYPE_FEDEX_2_DAY              = 'FEDEX_2_DAY';
    const SERVICE_TYPE_FEDEX_2_DAY_AM           = 'FEDEX_2_DAY_AM';
    const SERVICE_TYPE_FEDEX_EXPRESS_SAVER      = 'FEDEX_EXPRESS_SAVER'; // economy
    const SERVICE_TYPE_FEDEX_FIRST_FREIGHT      = 'FEDEX_FIRST_FREIGHT';
    const SERVICE_TYPE_FEDEX_FREIGHT_PRIORITY   = 'FEDEX_FREIGHT_PRIORITY';
    const SERVICE_TYPE_FEDEX_FREIGHT_ECONOMY    = 'FEDEX_FREIGHT_ECONOMY';
    const SERVICE_TYPE_FIRST_OVERNIGHT          = 'FIRST_OVERNIGHT';
    const SERVICE_TYPE_INTERNATIONAL_PRIORITY   = 'INTERNATIONAL_PRIORITY';
    const SERVICE_TYPE_INTERNATIONAL_FIRST      = 'INTERNATIONAL_FIRST ';
    const SERVICE_TYPE_INTERNATIONAL_ECONOMY    = 'INTERNATIONAL_ECONOMY';
    const SERVICE_TYPE_INTERNATIONAL_PRIORITY_DISTRIBUTION   = 'INTERNATIONAL_PRIORITY_DISTRIBUTION';
    const SERVICE_TYPE_INTERNATIONAL_PRIORITY_FREIGHT        = 'INTERNATIONAL_PRIORITY_FREIGHT';
    const SERVICE_TYPE_INTERNATIONAL_DISTRIBUTION_FREIGHT    = 'INTERNATIONAL_DISTRIBUTION_FREIGHT';
    const SERVICE_TYPE_INTERNATIONAL_ECONOMY_DISTRIBUTION    = 'INTERNATIONAL_PRIORITY';
    const SERVICE_TYPE_INTERNATIONAL_ECONOMY_FREIGHT         = 'INTERNATIONAL_PRIORITY';
    
    /* PACKAGING TYPES */
    const PACKAGE_TYPE_BOX              = 'FEDEX_BOX';
    const PACKAGE_TYPE_PAK              = 'FEDEX_PAK';
    const PACKAGE_TYPE_TUBE             = 'FEDEX_TUBE';
    const PACKAGE_TYPE_YOUR_PACKAGING   = 'YOUR_PACKAGING';
    const PACKAGE_TYPE_FEDEX_ENVELOPE   = 'FEDEX_ENVELOPE';
    
    /* WEIGHT */
    const WEIGHT_LB = 'LB';
    const WEIGHT_KG = 'KG';
    
    /* DIMENSIONS */
    const DIMENSIONS_IN = 'IN';

        
    /* Payment Types */
    const PAYMENT_TYPE_SENDER       = 'SENDER';
    const PAYMENT_TYPE_RECIPIENT    = 'RECIPIENT';
    const PAYMENT_TYPE_THIRD_PARTY  = 'THIRD_PARTY';
      
    /* Collection Types */
    const COLLECTION_TYPE_ANY                 = 'ANY';
    const COLLECTION_TYPE_GUARANTEED_FUNDS    = 'GUARANTEED_FUNDS';
    
    /* LABEL FORMATS */
    const LABEL_FORMAT_COMMON2D 		= 'COMMON2D';
    const LABEL_FORMAT_LABEL_DATA_ONLY 	= 'LABEL_DATA_ONLY';
     
    /* IMAGE OUTPUT TYPES */
    const IMAGE_TYPE_PDF 	= 'PDF';
    const IMAGE_TYPE_PNG 	= 'PNG';
    const IMAGE_TYPE_DPL 	= 'DPL';
    const IMAGE_TYPE_EPL2 	= 'EPL2';
    const IMAGE_TYPE_ZPLII 	= 'ZPLII';
    
    /* Label Paper Sizes */
    const LABEL_PAPER_4X6                       = 'PAPER_4X6';
    const LABEL_PAPER_4X8                       = 'PAPER_4X8';
    const LABEL_PAPER_4X9                       = 'PAPER_4X9';
    const LABEL_PAPER_7X4_75                    = 'PAPER_7X4.75';
    const LABEL_PAPER_8_5X11_BOTTOM_HALF_LABEL  = 'PAPER_8.5X11_BOTTOM_HALF_LABEL';
    const LABEL_PAPER_8_5X11_TOP_HALF_LABEL     = 'PAPER_8.5X11_TOP_HALF_LABEL';
    const LABEL_PAPER_LETTER                    = 'PAPER_LETTER';
                                                                                              
    /* Rate Request Types */
   const RATE_REQUEST_TYPE_ACCOUNT  = 'ACCOUNT';
   const RATE_REQUEST_TYPE_LIST     = 'LIST';
                           
   /* Customer Reference Types */
   const CUSTOMER_REFERENCE_TYPE_CUSTOMER_REFERENCE     = 'CUSTOMER_REFERENCE';
   const CUSTOMER_REFERENCE_TYPE_INVOICE_NUMBER         = 'INVOICE_NUMBER';
   const CUSTOMER_REFERENCE_TYPE_P_O_NUMBER             = 'P_O_NUMBER';
   const CUSTOMER_REFERENCE_TYPE_SHIPMENT_INTEGRITY     = 'SHIPMENT_INTEGRITY';

   /* Shipment Service Options */    
   const SERVICE_OPTION_ALCOHOL						= 'ALCOHOL';
   const SERVICE_OPTION_BROKER_SELECT_OPTION		= 'BROKER_SELECT_OPTION';
   const SERVICE_OPTION_CALL_BEFORE_DELIVERY		= 'CALL_BEFORE_DELIVERY';
   const SERVICE_OPTION_COD							= 'COD';
   const SERVICE_OPTION_CUSTOM_DELIVERY_WINDOW		= 'CUSTOM_DELIVERY_WINDOW';
   const SERVICE_OPTION_DANGEROUS_GOODS				= 'DANGEROUS_GOODS';
   const SERVICE_OPTION_DO_NOT_BREAK_DOWN_PALLETS	= 'DO_NOT_BREAK_DOWN_PALLETS';
   const SERVICE_OPTION_DRY_ICE						= 'DRY_ICE';
   const SERVICE_OPTION_EAST_COAST_SPECIAL			= 'EAST_COAST_SPECIAL';
   const SERVICE_OPTION_ELECTRONIC_TRADE_DOCUMENTS	= 'ELECTRONIC_TRADE_DOCUMENTS';
   const SERVICE_OPTION_EMAIL_NOTIFICATION			= 'EMAIL_NOTIFICATION';
   const SERVICE_OPTION_EXHIBITION_DELIVERY			= 'EXHIBITION_DELIVERY';
   const SERVICE_OPTION_EXHIBITION_PICKUP			= 'EXHIBITION_PICKUP';
   const SERVICE_OPTION_EXTREME_LENGTH				= 'EXTREME_LENGTH';
   const SERVICE_OPTION_FLATBED_TRAILER				= 'FLATBED_TRAILER';
   const SERVICE_OPTION_FOOD						= 'FOOD';
   const SERVICE_OPTION_FREIGHT_GURANTEE			= 'FREIGHT_GURANTEE';
   const SERVICE_OPTION_FUTURE_DAY_SHIPMENT			= 'FUTURE_DAY_SHIPMENT';
   const SERVICE_OPTION_HOLD_AT_LOCATION			= 'HOLD_AT_LOCATION';
   const SERVICE_OPTION_HOME_DELIVERY_PREMIUM		= 'HOME_DELIVERY_PREMIUM';
   const SERVICE_OPTION_INSIDE_DELIVERY				= 'INSIDE_DELIVERY';
   const SERVICE_OPTION_INSIDE_PICKUP				= 'INSIDE_PICKUP';
   const SERVICE_OPTION_LIFTGATE_DELIVERY			= 'LIFTGATE_DELIVERY';
   const SERVICE_OPTION_LIFTGATE_PICKUP				= 'LIFTGATE_PICKUP';
   const SERVICE_OPTION_LIMITED_ACCESS_DELIVERY		= 'LIMITED_ACCESS_DELIVERY';
   const SERVICE_OPTION_LIMITED_ACCESS_PICKUP		= 'LIMITED_ACCESS_PICKUP';
   const SERVICE_OPTION_PENDING_SHIPMENT			= 'PENDING_SHIPMENT';
   const SERVICE_OPTION_POISON						= 'POISON';
   const SERVICE_OPTION_PRE_DELIVERY_NOTIFICATION	= 'PRE_DELIVERY_NOTIFICATION';
   const SERVICE_OPTION_PROTECTION_FROM_FREEZING	= 'PROTECTION_FROM_FREEZING';
   const SERVICE_OPTION_REGIONAL_MALL_DELIVERY		= 'REGIONAL_MALL_DELIVERY';
   const SERVICE_OPTION_REGIONAL_MALL_PICKUP		= 'REGIONAL_MALL_PICKUP';
   const SERVICE_OPTION_RETURN_SHIPMENT				= 'RETURN_SHIPMENT';
   const SERVICE_OPTION_SATURDAY_DELIVERY			= 'SATURDAY_DELIVERY';
   const SERVICE_OPTION_SATURDAY_PICKUP				= 'SATURDAY_PICKUP';
   const SERVICE_OPTION_TOP_LOAD					= 'TOP_LOAD';

   /** Signature Options */
   const SIGNATURE_OPTION_SERVICE_DEFAULT 		= 'SERVICE_DEFAULT';
   const SIGNATURE_OPTION_NO_SIGNATURE_REQUIRED = 'NO_SIGNATURE_REQUIRED';
   const SIGNATURE_OPTION_INDIRECT 				= 'INDIRECT';
   const SIGNATURE_OPTION_DIRECT 				= 'DIRECT';
   const SIGNATURE_OPTION_ADULT 				= 'ADULT';
          
          
   const EMAIL_NOTIFICATION_AGGREGATE_TYPE_PER_PACKAGE  = 'PER_PACKAGE';
   const EMAIL_NOTIFICATION_AGGREGATE_TYPE_PER_SHIPMENT = 'PER_SHIPMENT';

   const EMAIL_NOTIFICATION_TYPE_BROKER         = 'BROKER';
   const EMAIL_NOTIFICATION_TYPE_OTHER          = 'OTHER';
   const EMAIL_NOTIFICATION_TYPE_SHIPPER        = 'SHIPPER';
   const EMAIL_NOTIFICATION_TYPE_RECIPIENT      = 'RECIPIENT';
   const EMAIL_NOTIFICATION_TYPE_THIRD_PARTY    = 'THIRD_PARTY';
   
   const EMAIL_NOTIFICATION_EVENT_ON_DELIVERY   = 'ON_DELIVERY';
   const EMAIL_NOTIFICATION_EVENT_ON_EXCEPTION  = 'ON_EXCEPTION';
   const EMAIL_NOTIFICATION_EVENT_ON_SHIPMENT   = 'ON_SHIPMENT';
   const EMAIL_NOTIFICATION_EVENT_ON_TENDER     = 'ON_TENDER';

   const EMAIL_NOTIFICATION_FORMAT_HTML     = 'HTML';
   const EMAIL_NOTIFICATION_FORMAT_TEXT     = 'TEXT';
   const EMAIL_NOTIFICATION_FORMAT_WIRELESS = 'WIRELESS';
           
   const CUSTOMS_TERMS_OF_SALE_CFR_OR_CPT     = 'CFR_OR_CPT';
   const CUSTOMS_TERMS_OF_SALE_CIF_OR_CIP     = 'CIF_OR_CIP';
   const CUSTOMS_TERMS_OF_SALE_DDP            = 'DDP';  
   const CUSTOMS_TERMS_OF_SALE_DDU            = 'DDU';  
   const CUSTOMS_TERMS_OF_SALE_EXW            = 'EXW';  
   const CUSTOMS_TERMS_OF_SALE_FOB_OR_FCA     = 'FOB_OR_FCA';  
  
  const CUSTOMS_DESTINATION_CONTROL_DEPARTMENT_OF_COMMERCE  = 'DEPARTMENT_OF_COMMERCE';
  const CUSTOMS_DESTINATION_CONTROL_DEPARTMENT_OF_STATE     = 'DEPARTMENT_OF_STATE';

  const CUSTOMS_PURPOSE_GIFT                = 'GIFT';
  const CUSTOMS_PURPOSE_NOT_SOLD            = 'NOT_SOLD';
  const CUSTOMS_PURPOSE_PERSONAL_EFFECTS    = 'PERSONAL_EFFECTS';
  const CUSTOMS_PURPOSE_REPAIR_AND_RETURN   = 'REPAIR_AND_RETURN';
  const CUSTOMS_PURPOSE_SAMPLE              = 'SAMPLE';
  const CUSTOMS_PURPOSE_SOLD                = 'SOLD';
  
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

    /**
     * __construct
     * 
     * @param array $parameters
     */
   public function __construct(array $parameters = array()){
       
       foreach(array('authKey','authPassword','accountNumber','meterNumber','clientProductId','clientProductVersion') as $requiredParameter){
           if(!isset($parameters[$requiredParameter])){
               throw new \Exception(sprintf('Parameter %s is Required to Create a Request Object',$requiredParameter));
           }
           $setterMethod = 'set'.ucwords($requiredParameter);
           $this->$setterMethod($parameters[$requiredParameter]);
       }
       
       $this->originalRequestParams = $this->requestParams; // to reset request       
   } 
   
   /**
    * getRequest
    * 
    * @return array
    */
   public function getRequest(){
       return $this->requestParams;
   }

    /**
     * setAuthKey
     * 
     * @param string $authKey
     * 
     * @return Request
     */
    public function setAuthKey($authKey){
        $this->requestParams['WebAuthenticationDetail']['UserCredential']['Key'] = $authKey;
        return $this;
    }
    /**
     * getAuthKey
     * 
     * @return string
     */
    public function getAuthKey(){
        return $this->requestParams['WebAuthenticationDetail']['UserCredential']['Key'];
    }

    /**
     * setAuthPassword
     * 
     * @param string $authPassword
     * 
     * @return Request
     */
    public function setAuthPassword($authPassword){
        $this->requestParams['WebAuthenticationDetail']['UserCredential']['Password'] = $authPassword;
        return $this;
    }
    /**
     * getAuthPassword
     * 
     * @return string
     */
    public function getAuthPassword(){
        return $this->requestParams['WebAuthenticationDetail']['UserCredential']['Password'];
    }

    /**
     * setAccountNumber
     * 
     * @param string $accountNumber
     * 
     * @return Request
     */
    public function setAccountNumber($accountNumber){
        $this->requestParams['ClientDetail']['AccountNumber'] = $accountNumber;
        return $this;
    }
    /**
     * getAccountNumber
     * 
     * @return string
     */
    public function getAccountNumber(){
        return $this->requestParams['ClientDetail']['AccountNumber'];
    }
    
    
    /**
     * setMeterNumber
     * 
     * @param string $meterNumber
     * 
     * @return Request
     */
    public function setMeterNumber($meterNumber){
        $this->requestParams['ClientDetail']['MeterNumber'] = $meterNumber;
        return $this;
    }
    
    /**
     * getMeterNumber
     * 
     * @return string
     */
    public function getMeterNumber(){
        return $this->requestParams['ClientDetail']['MeterNumber'];
    }


    /**
     * setRequestedShipment
     * 
     * @param string $requestedShipment
     * 
     * @return Request
     */
    public function setRequestedShipment(array $requestedShipment){
        $this->requestParams['RequestedShipment'] = $requestedShipment;
        return $this;
    }
    
    /**
     * getRequestedShipment
     * 
     * @return array
     */
    public function getRequestedShipment(){
        return $this->requestParams['RequestedShipment'];
    }

    /**
     * mergeWithRequest
     * 
     * @param array $requestData
     */
    public function mergeWithRequest(array $requestData){
        $this->requestParams = array_merge($this->requestParams,$requestData);
        return $this;
    }
    
    /**
     * resetRequest
     * 
     * @return Request
     */
    public function resetRequest(){
        $this->requestParams = $this->originalRequestParams;
        return $this;
    }
    
    
    
    /**
     * setClientProductId
     * 
     * @param string $clientProductId
     * 
     * @return Request
     */
    public function setClientProductId($clientProductId){
        $this->requestParams['ClientDetail']['ClientProductId'] = $clientProductId;
        return $this;
    }
    /**
     * getClientProductId
     * 
     * @return string
     */
    public function getClientProductId(){
        return $this->requestParams['ClientDetail']['ClientProductId'];
    }
    
    
    /**
     * setClientProductVersion
     * 
     * @param string $clientProductVersion
     * 
     * @return Request
     */
    public function setClientProductVersion($clientProductVersion){
        $this->requestParams['ClientDetail']['ClientProductVersion'] = $clientProductVersion;
        return $this;
    }
    /**
     * getClientProductVersion
     * 
     * @return string
     */
    public function getClientProductVersion(){
        return $this->requestParams['ClientDetail']['ClientProductVersion'];
    }
    
    

   /**
    * setReturnTransitAndCommit
    * @param Boolean $returnTransitAndCommit
    * 
    * @return RatesRequest
    */
   public function setReturnTransitAndCommit($returnTransitAndCommit){
       $this->requestParams['ReturnTransitAndCommit'] = $returnTransitAndCommit;
       return $this;
   }
   
   /**
    * getReturnTransitAndCommit
    * 
    * @return bool
    */
   public function getReturnTransitAndCommit(){
       return $this->requestParams['ReturnTransitAndCommit'];
   }

   /**
    * setShipTimestamp
    * @param mixed $shipTimestamp
    * 
    * @return RatesRequest
    */
   public function setShipTimestamp($shipTimestamp){
       if($shipTimestamp instanceof \DateTime){
           $shipTimestamp = $shipTimestamp->format('c');
       }
       $this->requestParams['RequestedShipment']['ShipTimestamp'] = $shipTimestamp;
       return $this;
   }
   
   /**
    * getShipTimestamp
    * 
    * @return string
    */
   public function getShipTimestamp(){
       return isset($this->requestParams['RequestedShipment']['ShipTimestamp']) ? $this->requestParams['RequestedShipment']['ShipTimestamp'] : null;
   }
   
   /**
    * setPackagingType
    * @param string $packagingType
    * 
    * @return RatesRequest
    */
   public function setPackagingType($packagingType){
       $this->requestParams['RequestedShipment']['PackagingType'] = $packagingType;
       return $this;
   }
   
   /**
    * getPackagingType
    * 
    * @return string
    */
   public function getPackagingType(){
       return isset($this->requestParams['RequestedShipment']['PackagingType']) ? $this->requestParams['RequestedShipment']['PackagingType'] : null;
   }
   
   
   /**
    * setTotalInsuredValue
    * @param string $totalInsuredValue
    * @param string $currency
    * 
    * @return RatesRequest
    */
   public function setTotalInsuredValue($totalInsuredValue, $currency = 'USD'){
       $this->requestParams['RequestedShipment']['TotalInsuredValue'] = array('Amount'=> $totalInsuredValue,'Currency'=> $currency);
       return $this;
   }
   
   /**
    * getTotalInsuredValue
    * 
    * @return array
    */
   public function getTotalInsuredValue(){
       return isset($this->requestParams['RequestedShipment']['TotalInsuredValue']) ? $this->requestParams['RequestedShipment']['TotalInsuredValue'] : array();
   }
   
   
   /**
    * setTotalWeight
    * @param string $totalWeight
    * @param string $totalWeightUnit
    * 
    * @return RatesRequest
    */
   public function setTotalWeight($totalWeight, $totalWeightUnit = 'LB'){
       $this->requestParams['RequestedShipment']['TotalWeight'] = array('Value'=> $totalWeight,'Units'=> $totalWeightUnit);
       return $this;
   }
   
   /**
    * getTotalWeight
    * 
    * @return array
    */
   public function getTotalWeight(){
       return isset($this->requestParams['RequestedShipment']['TotalWeight']) ? $this->requestParams['RequestedShipment']['TotalWeight'] : array();
   }

          
   /**
    * setShipper
    * @param string $name
    * @param string $company
    * @param string $phone
    * @param string $address1
    * @param string $addres2
    * @param string $city
    * @param string $state
    * @param string $zipcode
    * @param string $country - 2 DIGIT Code
    * 
    * @return RatesRequest
    */
   public function setShipper($name, $company, $phone, $address1, $addres2, $city, $state, $zipcode, $country){
       $this->requestParams['RequestedShipment']['Shipper'] = array(
            'Contact' => array(
                'PersonName' => $name,
                'CompanyName' => $company,
                'PhoneNumber' => $phone),
                'Address' => array(
                    'StreetLines' => array($address1,$addres2),
                    'City' => $city,
                    'StateOrProvinceCode' => $state,
                    'PostalCode' => $zipcode,
                    'CountryCode' => $country
               )
       );
       return $this;
   }
   
   /**
    * getShipper
    * 
    * @return array
    */
   public function getShipper(){
       return isset($this->requestParams['RequestedShipment']['Shipper']) ? $this->requestParams['RequestedShipment']['Shipper'] : array();
   }

   /**
    * setRecipient
    * @param string $name
    * @param string $company
    * @param string $phone
    * @param string $address1
    * @param string $addres2
    * @param string $city
    * @param string $state
    * @param string $zipcode
    * @param string $country - 2 DIGIT Code
    * 
    * @return RatesRequest
    */
   public function setRecipient($name, $company, $phone, $address1, $addres2, $city, $state, $zipcode, $country, $isResidential = true){
       $this->requestParams['RequestedShipment']['Recipient'] = array(
            'Contact' => array(
                'PersonName' => $name,
                'CompanyName' => $company,
                'PhoneNumber' => $phone),
                'Address' => array(
                    'StreetLines' => array($address1,$addres2),
                    'City' => $city,
                    'StateOrProvinceCode' => $state,
                    'PostalCode' => $zipcode,
                    'CountryCode' => $country,
                    'Residential' => $isResidential,
               )
       );
       return $this;
   }
   
   /**
    * getRecipient
    * 
    * @return array
    */
   public function getRecipient(){
       return isset($this->requestParams['RequestedShipment']['Recipient']) ? $this->requestParams['RequestedShipment']['Recipient'] : array();
   }
                    
                 
   /**
    * setShippingChargesPayment
    * @param string $paymentType
    * @param string $accountNumber
    * @param string $countryCode - 2 DIGIT Code
    * 
    * @return RatesRequest
    */
   public function setShippingChargesPayment($paymentType, $accountNumber, $countryCode){
       $this->requestParams['RequestedShipment']['ShippingChargesPayment'] = array(
        'PaymentType' => $paymentType, 
        'Payor' => array(
            'AccountNumber' => $accountNumber, 
            'CountryCode' => $countryCode
         )
       );
       return $this;
   }
   
   /**
    * getShippingChargesPayment
    * 
    * @return array
    */
   public function getShippingChargesPayment(){
       return isset($this->requestParams['RequestedShipment']['ShippingChargesPayment']) ? $this->requestParams['RequestedShipment']['ShippingChargesPayment'] : array();
   }
   
            
   /**
    * setRateRequestTypes
    * @param array $types
    * 
    * @return RatesRequest
    */
   public function setRateRequestTypes(array $types){
       $this->requestParams['RequestedShipment']['RateRequestTypes'] = $types;
       return $this;
   }
   
   /**
    * getShippingChargesPayment
    * 
    * @return array
    */
   public function getRateRequestTypes(){
       return isset($this->requestParams['RequestedShipment']['RateRequestTypes']) ? $this->requestParams['RequestedShipment']['RateRequestTypes'] : array();
   }
   
   
   /**
    * setPackageCount
    * @param int $packageCount
    * 
    * @return RatesRequest
    */
   public function setPackageCount($packageCount){
       $this->requestParams['RequestedShipment']['PackageCount'] = $packageCount;
       return $this;
   }
   
   /**
    * getPackageCount
    * 
    * @return array
    */
   public function getPackageCount(){
       return isset($this->requestParams['RequestedShipment']['PackageCount']) ? $this->requestParams['RequestedShipment']['PackageCount'] : null;
   }
   
   
   /**
    * setPackageDetail
    * @param string $packageDetail
    * 
    * @return RatesRequest
    */
   public function setPackageDetail($packageDetail){
       $this->requestParams['RequestedShipment']['PackageDetail'] = $packageDetail;
       return $this;
   }
   
   
   /**
    * getPackageDetail
    * 
    * @return array
    */
   public function getPackageDetail(){
       return isset($this->requestParams['RequestedShipment']['PackageDetail']) ? $this->requestParams['RequestedShipment']['PackageDetail'] : null;
   }
   
   
   /**
    * setDropoffType
    * @param string $dropoffType
    * 
    * @return RatesRequest
    */
   public function setDropoffType($dropoffType){
       $this->requestParams['RequestedShipment']['DropoffType'] = $dropoffType;
       return $this;
   }
   
   
   /**
    * getDropoffType
    * 
    * @return array
    */
   public function getDropoffType(){
       return isset($this->requestParams['RequestedShipment']['DropoffType']) ? $this->requestParams['RequestedShipment']['DropoffType'] : null;
   }
   
   /**
    * setRecipient
    * @param int $packageCount
    * @param int $weight
    * @param int $length
    * @param int $width
    * @param int $height
    * @param string $weightUnit
    * @param string $dimensionsUnit
    * 
    * @return RatesRequest
    */
   public function setRequestedPackageLineItems($sequenceNumber, $groupPackageCount, $weight, $length, $width, $height, $weightUnit = 'LB', $dimensionsUnit = 'IN'){
      $requestedPackageLineItem = array(
        'SequenceNumber'=> $sequenceNumber,
        'GroupPackageCount'=> $groupPackageCount,
        'Weight' => array(
            'Value' => $weight,
            'Units' => $weightUnit
        ),
       );
       if($length&&$width&&$height){
         $requestedPackageLineItem = array_merge($requestedPackageLineItem,array(
         'Dimensions' => array(
            'Length' => $length,
            'Width' => $width,
            'Height' => $height,
            'Units' => $dimensionsUnit
        )));
       }
       if(!isset($this->requestParams['RequestedShipment']['RequestedPackageLineItems'])){
           $this->requestParams['RequestedShipment']['RequestedPackageLineItems'] = array();
       }
       $this->requestParams['RequestedShipment']['RequestedPackageLineItems'] = $requestedPackageLineItem;
       return $this;
   }
   /**
    * addRequestedPackageLineItems
    * @param int $sequenceNumber
    * @param int $groupPackageCount
    * @param int $weight
    * @param int $length
    * @param int $width
    * @param int $height
    * @param string $weightUnit
    * @param string $dimensionsUnit
    * 
    * @return this
    */
   public function addRequestedPackageLineItems($sequenceNumber, $groupPackageCount, $weight, $length, $width, $height, $weightUnit = 'LB', $dimensionsUnit = 'IN'){
      $requestedPackageLineItem = array(
        'SequenceNumber'=> $sequenceNumber,
        'GroupPackageCount'=> $groupPackageCount,
        'Weight' => array(
            'Value' => $weight,
            'Units' => $weightUnit
        ),
       );
       if($length&&$width&&$height){
         $requestedPackageLineItem = array_merge($requestedPackageLineItem,array(
         'Dimensions' => array(
            'Length' => $length,
            'Width' => $width,
            'Height' => $height,
            'Units' => $dimensionsUnit
        )));
       }
       if(!isset($this->requestParams['RequestedShipment']['RequestedPackageLineItems'])){
           $this->requestParams['RequestedShipment']['RequestedPackageLineItems'] = array();
       }
       $this->requestParams['RequestedShipment']['RequestedPackageLineItems'][] = $requestedPackageLineItem;
       return $this;
   }
   
   /**
    * getRequestedPackageLineItems
    * 
    * @return array
    */
   public function getRequestedPackageLineItems(){
       return isset($this->requestParams['RequestedShipment']['RequestedPackageLineItems']) ? $this->requestParams['RequestedShipment']['RequestedPackageLineItems'] : array();
   }
   

   /**
    * setCustomsClearanceDetail
    */
   public function setCustomsClearanceDetail(
    $paidBy,
    $totalAmount,
    $itemQty, 
    $itemPrice, 
    $packageCount, 
    $customsName,
    $customsDescription, 
    $manufacturedIn, 
    $packageWeight, 
    $isDocuments, 
    $destinationControl,
    $destinationCountry,
    $destinationUser,
    $packageWeightUnit = 'LB'
  ){
      
         $customsClearanceDetails =   array(
            'DutiesPayment' => array(
                'PaymentType' => $paidBy, // valid values RECIPIENT, SENDER and THIRD_PARTY
                'Payor' => array(
                    'AccountNumber' => null,
                    'CountryCode' => null
                )
            ),
            'DocumentContent' => $isDocuments ? 'DOCUMENTS_ONLY' : 'NON_DOCUMENTS',                                                                                            
            'CustomsValue' => array(
                'Currency' => 'USD', 
                'Amount' => $totalAmount
            ),
        'Commodities' => array(
            '0' => array(
                'Name' => $customsName,
                'NumberOfPieces' => $packageCount,
                'Description' => $customsDescription,
                'CountryOfManufacture' => $manufacturedIn,
                'Weight' => array(
                    'Units' => $packageWeightUnit, 
                    'Value' => $packageWeight
                ),
                'Quantity' => $itemQty,
                'QuantityUnits' => 'EA',
                'UnitPrice' => array(
                    'Currency' => 'USD', 
                    'Amount' => $itemPrice
                ),
                'CustomsValue' => array(
                    'Currency' => 'USD', 
                    'Amount' => $totalAmount
                )
            )
        ),
        'ExportDetail' => array(
            'B13AFilingOption' => 'NOT_REQUIRED',
            'DestinationControlDetail' => array(
                'StatementTypes' => array($destinationControl)
            ),
        )
    );
    
    if($destinationControl == self::CUSTOMS_DESTINATION_CONTROL_DEPARTMENT_OF_STATE){
        $customsClearanceDetails['ExportDetail']['DestinationControlDetail']['DestinationCountries'] = $destinationCountry;
        $customsClearanceDetails['ExportDetail']['DestinationControlDetail']['EndUser'] = $destinationUser;
    }

    if($paidBy == self::PAYMENT_TYPE_SENDER){
        $customsClearanceDetails['DutiesPayment']['Payor']['AccountNumber']  = $this->getAccountNumber();
        $customsClearanceDetails['DutiesPayment']['Payor']['CountryCode']    = 'US';
    } 
    
    $this->requestParams['RequestedShipment']['CustomsClearanceDetail'] = $customsClearanceDetails;
    return $this;  
   }
   
   /**
    * getCustomsClearanceDetail
    */
   public function getCustomsClearanceDetail(){
       return isset($this->requestParams['RequestedShipment']['CustomsClearanceDetail']) ? $this->requestParams['RequestedShipment']['CustomsClearanceDetail'] : array();
   }
   
   
   /**
    * setCommercialInvoice
    */
   public function setCommercialInvoice($invoiceNumber,$termsOfSale,$comments = null, $purpose = null, $originatorName=null){
       $customsClearanceDetails = array(
        'CommercialInvoice' => array(
            'CustomsInvoiceNumber' => $invoiceNumber,
            'TermsOfSale' => $termsOfSale
        )
       );
       if($originatorName){
           $customsClearanceDetails['CommercialInvoice']['OriginatorName'] = $originatorName;
       }
       if($comments){
           $customsClearanceDetails['CommercialInvoice']['Comments'] = $comments;
       }
       if($purpose){
           $customsClearanceDetails['CommercialInvoice']['Purpose'] = $purpose;
       }
       
       $this->requestParams['RequestedShipment']['CustomsClearanceDetail'] = array_merge(
        $this->getCustomsClearanceDetail(),
        $customsClearanceDetails
       );
       
       $this->requestParams['RequestedShipment']['ShippingDocumentSpecification'] = array(
        'ShippingDocumentTypes' => array('COMMERCIAL_INVOICE'),
        'CommercialInvoiceDetail' => array(
            'Format' => array(
                'Dispositions' => array('RETURNED'),
                'ImageType' => self::IMAGE_TYPE_PDF,
                'StockType' => 'PAPER_LETTER',
            ),
         )
       );
       
       return $this;
   }
   
   /**
    * setSpecialServicesRequested
    */
   public function setSpecialServicesRequested(array $specialServices){
        $this->requestParams['RequestedShipment']['SpecialServicesRequested'] = array(
            'SpecialServiceTypes' => $specialServices
        );
       return $this;
   }
   
   /**
    * getSpecialServicesRequested
    */
   public function getSpecialServicesRequested(){
       return isset($this->requestParams['RequestedShipment']['SignatureOptionDetail']) ? $this->requestParams['RequestedShipment']['SignatureOptionDetail'] : array();
   }
   
   /**
    * setSignatureOptionDetail
    */
   public function setSignatureOptionDetail($signatureOption){
       $this->requestParams['RequestedShipment']['SignatureOptionDetail'] = array(
            'OptionType' => $signatureOption
        );
       return $this;
   }
   
   /**
    * getSignatureOptionDetail
    */
   public function getSignatureOptionDetail(){
       return isset($this->requestParams['RequestedShipment']['SignatureOptionDetail']) ? $this->requestParams['RequestedShipment']['SignatureOptionDetail'] : array();
   }
   
   /**
    * setLabelSpecification
    */
   public function setLabelSpecification($labelFormat, $labelFileType, $labelPaperSize){
       $this->requestParams['RequestedShipment']['LabelSpecification'] = array(
            'LabelFormatType' => $labelFormat, 
            'ImageType' => $labelFileType, 
            'LabelStockType' => $labelPaperSize
       );
       return $this;
       
   }
   
   /**
    * getLabelSpecification
    */
   public function getLabelSpecification(){
       return isset($this->requestParams['RequestedShipment']['LabelSpecification']) ? $this->requestParams['RequestedShipment']['LabelSpecification'] : array();
   }

   
   /**
    * setServiceType
    */
   public function setServiceType($serviceType){
       $this->requestParams['RequestedShipment']['ServiceType'] = $serviceType;
       return $this;
       
   }
   
   /**
    * getServiceType
    */
   public function getServiceType(){
       return isset($this->requestParams['RequestedShipment']['ServiceType']) ? $this->requestParams['RequestedShipment']['ServiceType'] : array();
   }


   /**
    * setTrackingId
    */
   public function setTrackingId($trackingIdType, $trackingNumber){
       $this->requestParams['TrackingId'] = array('TrackingIdType' => $trackingIdType, 'TrackingNumber' => $trackingNumber);
       return $this;
       
   }
   
   /**
    * getTrackingId
    */
   public function getTrackingId(){
       return isset($this->requestParams['TrackingId']) ? $this->requestParams['TrackingId'] : array();
   }


   /**
    * setDeletionControl
    */
   public function setDeletionControl($deleteControl){
       $this->requestParams['DeletionControl'] = $deleteControl;
       return $this;
       
   }
   
   /**
    * getDeletionControl
    */
   public function getDeletionControl(){
       return isset($this->requestParams['DeletionControl']) ? $this->requestParams['DeletionControl'] : null;
   }
   
   
   /**
    * setCustomerTransactionId
    */
   public function setCustomerTransactionId($customerTransactionId){
       $this->requestParams['TransactionDetail']['CustomerTransactionId'] = $customerTransactionId;
       return $this;
       
   }
   
   /**
    * getCustomerTransactionId
    */
   public function getCustomerTransactionId(){
       return isset($this->requestParams['TransactionDetail']['CustomerTransactionId']) ? 
        $this->requestParams['TransactionDetail']['CustomerTransactionId'] : null;
   }
   
   /**
    * setRequestTimestamp
    */
   public function setRequestTimestamp($requestTimestamp){
       $this->requestParams['RequestTimestamp'] = $requestTimestamp;
       return $this;
       
   }
   
   /**
    * getRequestTimestamp
    */
   public function getRequestTimestamp(){
       return isset($this->requestParams['RequestTimestamp']) ? $this->requestParams['RequestTimestamp'] : null;
   }
   
   
   /**
    * addAddressesToValidate
    */
   public function addAddressesToValidate($addressId, $company, $address1, $address2, $city, $state, $zipcode, $country){
       $address = array(
        'AddressId' => $addressId,
        'Address' => array(
            'StreetLines' => array($address1,$address2),
            'City' => $city,
            'StateOrProvinceCode' => $state,
            'PostalCode' => $zipcode,
            'CompanyName' => $company,
            'CountryCode' => $country,
        )
       );
       if(empty($company)){
           unset($address['Address']['CompanyName']);
       }
       if(empty($address2)){
           unset($address['Address']['StreetLines'][1]);
       }
                                               
       $this->requestParams['AddressesToValidate'][] = $address;
       return $this;
       
   }
   
   /**
    * getAddressesToValidate
    */
   public function getAddressesToValidate(){
       return isset($this->requestParams['AddressesToValidate']) ? $this->requestParams['AddressesToValidate'] : array();
   }

   /**
    * setsetEmailNotificationDetail
    */
   public function setEmailNotificationDetail($personalMessage,$aggregationType){
       $this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail'] = array(
        'AggregationType' => $aggregationType, // PER_PACKAGE , PER_SHIPMENT
        'PersonalMessage' => $personalMessage,
        'Recipients' => array(
            
        )
       );
       return $this;
       
   }

   /**
    * getsetEmailNotificationDetail
    */
   public function getEmailNotificationDetail(){
       return isset($this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail']) ? 
         $this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail'] : array();
   }
   
   
   
   /**
    * addEmailNotificationDetailRecipient
    */
   public function addEmailNotificationDetailRecipient($recipientType,$emailAddress,array $deliveryEvents, $format = 'HTML', $languageCode = 'EN'){
       if(!isset($this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail'])){
           $this->setEmailNotificationDetail(null,'PER_SHIPMENT');
       }
       
       $count = count($this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail']['Recipients']);
       $this->requestParams['RequestedShipment']['SpecialServicesRequested']['EMailNotificationDetail']['Recipients'][] = array(
        'EMailNotificationRecipientType' => $recipientType, //BROKER, OTHER, RECIPIENT, SHIPPER, THIRD_PARTY
        'EMailAddress' => $emailAddress,
        'NotificationEventsRequested' => $deliveryEvents, //ON_DELIVERY, ON_EXCEPTION, ON_SHIPMENT, ON_TENDER
        'Format' => $format, //HTML, TEXT, WIRELESS
        'Localization' => array('LanguageCode' => $languageCode), 
       );
       return $this;
   }

   /**
    * getMasterTrackingId
    */
   public function getMasterTrackingId(){
       return isset($this->requestParams['RequestedShipment']['MasterTrackingId']) ? $this->requestParams['RequestedShipment']['MasterTrackingId'] : null;
   }

   /**
    * setMasterTrackingId
    */
   public function setMasterTrackingId($masterTrackingId){
       $this->requestParams['RequestedShipment']['MasterTrackingId'] = $masterTrackingId;
       return $this;
       
  }
}
