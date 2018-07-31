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
 * Response
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Response{
    
    protected $response, 
              $responeArray = array();
    
    const STATUS_SUCCESS 	= 'SUCCESS';
    const STATUS_FAILURE 	= 'FAILURE';
    const STATUS_ERROR 		= 'ERROR';
    const STATUS_WARNING 	= 'WARNING';
    const STATUS_NOTE 		= 'SUCCESS';
    
    /**
     * 
     */
    public function __construct($response){
        $this->response = $response;
        if($response instanceof \SimpleXMLElement){
            $this->responseArray = $this->simpleXmlToArray($response);
        }    
    }
    
    /**
     * simpleXmlToArray
     * 
     * @param \SimpleXMLElement $e
     */
    protected function simpleXmlToArray(\SimpleXMLElement $e){
        return json_decode(json_encode($e),true);        
    }
    
    /**
     * getResponse
     */
    public function getResponse(){
    	return $this->response;
    }
    /**
     * getResponseArray
     * 
     * @return array $responseArray
     */
    public function getResponseArray(){
    	return $this->responseArray;
    }
    /**
     * getStatus
     * @return string
     */
    public function getStatus(){
    	return $this->response->HighestSeverity;
    }
    
    /**
     * isSuccess
     * @return boolean
     */
    public function isSuccess(){
    	return in_array($this->response->HighestSeverity, array(self::STATUS_SUCCESS,self::STATUS_NOTE,self::STATUS_WARNING));
    }
    
    /**
     * isError
     * @return boolean
     */
    public function isError(){
    	return in_array($this->response->HighestSeverity, array(self::STATUS_ERROR,self::STATUS_FAILURE));
    }
    
    /**
     * isNote
     * @return boolean
     */
    public function isNote(){
    	return $this->response->HighestSeverity == self::STATUS_NOTE;
    }
    
    /**
     * isWarning
     * @return boolean
     */
    public function isWarning(){
    	return $this->response->HighestSeverity == self::STATUS_WARNING;
    }
    
    /**
     * getMessage
     * @return string error
     */
    public function getMessage(){
    	return $this->response->Notifications->Message;
    }
    
    /**
     * hasShippingLabel
     * @return boolean
     */
    public function hasShippingLabel(){
    	return isset($this->response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
    }
    
    /**
     * getShippingLabel
     * @return PDF/IMAGE DATA
     */
    public function getShippingLabel(){
    	if($this->hasShippingLabel()){
    		return $this->response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image;
    	}
    	return null;
    }
    
    /**
     * hasShippingDocument
     * 
     * @return boolean
     */
    public function hasShippingDocument(){
    	return isset($this->response->CompletedShipmentDetail->ShipmentDocuments->Parts->Image);
    }
    
    /**
     * getShippingDocument
     * @return PDF/IMAGE DATA
     */
    public function getShippingDocument(){
    	if($this->hasShippingDocument()){
    		return $this->response->CompletedShipmentDetail->ShipmentDocuments->Parts->Image;
    	}
    }
    
    /**
     * getShippingDocumentType
     * @return string|NULL
     */
    public function getShippingDocumentType(){
    	return isset($this->response->CompletedShipmentDetail->ShipmentDocuments->Type) ?
    	$this->response->CompletedShipmentDetail->ShipmentDocuments->Type : null;
    }
    
    /**
     * hasTrackingNumber
     * @return boolean
     */
    public function hasTrackingNumber(){
    	return isset($this->response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber);
    }
    
    /**
     * getTrackingNumber
     * @return string|NULL
     */
    public function getTrackingNumber(){
    	return $this->hasTrackingNumber() ? $this->response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber : null;
    }
    
    /**
     * hasMasterTrackingId
     * @return boolean
     */
    public function hasMasterTrackingId(){
    	return isset($this->response->CompletedShipmentDetail->MasterTrackingId);
    }
    
    /**
     * getMasterTrackingId
     * @return StdClass|NULL
     */
    public function getMasterTrackingId(){
    	return $this->hasMasterTrackingId() ? $this->response->CompletedShipmentDetail->MasterTrackingId : null;
    }
    
    /**
     * hasShippingCost
     */
    public function hasShippingCost(){
    	return isset($this->response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails);
    }
    
    /**
     * getShippingCost
     * @return number
     */
    public function getShippingCost(){
    	return $this->hasShippingCost() ? $this->response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails[0]->TotalNetCharge->Amount : 0;
    }
    
    /**
     * hasPackageCost
     */
    public function hasPackageCost(){
    	return isset($this->response->CompletedShipmentDetail->CompletedPackageDetails->PackageRating->PackageRateDetails);
    }
    
    /**
     * getPackageCost
     */
    public function getPackageCost(){
    	return $this->hasPackageCost() ? $this->response->CompletedShipmentDetail->CompletedPackageDetails->PackageRating->PackageRateDetails[0]->NetCharge->Amount : 0;
    }
    
    /**
     * getTrackingIdType
     * @return string|NULL
     */
    public function getTrackingIdType(){
    	return $this->hasTrackingNumber() ? $this->response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingIdType : null;
    }
    
    /**
     * hasTrackingIdType
     * 
     * @return bool
     */
    public function hasTrackingIdType(){
    	return isset($this->response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingIdType);
    }
    
    /**
     * hasProposedAddressDetails
     * 
     * @return bool
     */
    public function hasProposedAddressDetails(){
    	return isset($this->response->AddressResults->ProposedAddressDetails);
    }
    
    /**
     * getProposedAddressDetails
     * 
     * @return string|null
     */
    public function getProposedAddressDetails(){
    	return $this->hasProposedAddressDetails() ? $this->response->AddressResults->ProposedAddressDetails : null;
    }
    
}