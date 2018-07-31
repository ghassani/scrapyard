<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Authorize;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Response
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Response extends BaseResponse
{

    /** $responseMap enumeration
     *  http://www.authorize.net/support/merchant/Transaction_Response/Transaction_Response.htm
     */
    protected $responseMap = array(
        'response_code',
        'response_subcode',
        'response_reason_code',
        'response_reason_text',
        'authorization_code',
        'avs_response',
        'transaction_id',
        'invoice_number',
        'description',
        'amount',
        'method',
        'transaction_type',
        'customer_id',
        'first_name',
        'last_name',
        'company',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'fax',
        'email_address',
        'ship_to_first_name',
        'ship_to_last_name',
        'ship_to_company',
        'ship_to_address',
        'ship_to_city',
        'ship_to_state',
        'ship_to_zipcode',
        'ship_to_country',
        'tax',
        'duty',
        'freight',
        'tax_exempt',
        'purchase_order_number',
        'md5_hash',
        'card_code_response',
        'cardholder_authentication_verification_response'
    );
    /**
     *
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
        parent::__construct($content,$status,$headers);

        if (stripos($content,'The merchant login ID or password is invalid or the account is inactive.') !== false) {
            throw new AuthorizeException('Authorize Account Credentials are Invalid');
        }

        $_responseArray = explode(Request::DELIM_CHAR,$content);
        $responseArray = array();
        foreach ($this->responseMap as $responseField => $responseFieldName) {
            $responseArray[$responseFieldName] = isset($_responseArray[$responseField]) ? $_responseArray[$responseField] : null;
        }
        $this->responseArray = $responseArray;
    }

    /**
     * getResponseCode
     *
     * @return string
     */
    public function getResponseCode()
    {
        return $this->getResponseParameter('response_code');
    }

    /**
     * getResponseSubcode
     *
     * @return string
     */
    public function getResponseSubcode()
    {
        return $this->getResponseParameter('response_subcode');
    }

    /**
     * getResponseReasonCode
     *
     * @return string
     */
    public function getResponseReasonCode()
    {
        return $this->getResponseParameter('response_reason_code');
    }

    /**
     * getResponseReasonText
     *
     * @return string
     */
    public function getResponseReasonText()
    {
        return $this->getResponseParameter('response_reason_text');
    }

    /**
     * getAuthorizationCode
     *
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->getResponseParameter('authorization_code');
    }

    /**
     * getAvsResponse
     *
     * @return string
     */
    public function getAvsResponse()
    {
        return $this->getResponseParameter('avs_response');
    }

    /**
     * getTransactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getResponseParameter('transaction_id');
    }

    /**
     * getInvoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->getResponseParameter('invoice_number');
    }

    /**
     * getAmount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->getResponseParameter('amount');
    }
    
    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getResponseParameter('description');
    }

    /**
     * getFirstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getResponseParameter('first_name');
    }

    /**
     * getLastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getResponseParameter('last_name');
    }

    /**
     * getCompany
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->getResponseParameter('company');
    }

    /**
     * getAddress
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getResponseParameter('address');
    }

    /**
     * getCity
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getResponseParameter('city');
    }

    /**
     * getState
     *
     * @return string
     */
    public function getState()
    {
        return $this->getResponseParameter('state');
    }

    /**
     * getZipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->getResponseParameter('zipcode');
    }

    /**
     * getCountry
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getResponseParameter('country');
    }

    /**
     * getPhone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getResponseParameter('phone');
    }

    /**
     * getFax
     *
     * @return string
     */
    public function getFax()
    {
        return isset($this->responseArray[18]) ? $this->responseArray[18] : null;
    }

    /**
     * getEmailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->getResponseParameter('email_address');
    }

    /**
     * getShipToFirstName
     *
     * @return string
     */
    public function getShipToFirstName()
    {
        return $this->getResponseParameter('ship_to_first_name');
    }

    /**
     * getShipToLastName
     *
     * @return string
     */
    public function getShipToLastName()
    {
        return $this->getResponseParameter('ship_to_last_name');
    }

    /**
     * getShipToCompany
     *
     * @return string
     */
    public function getShipToCompany()
    {
        return $this->getResponseParameter('ship_to_company');
    }

    /**
     * getShipToAddress
     *
     * @return string
     */
    public function getShipToAddress()
    {
        return $this->getResponseParameter('ship_to_address');
    }

    /**
     * getShipToCity
     *
     * @return string
     */
    public function getShipToCity()
    {
        return $this->getResponseParameter('ship_to_city');
    }

    /**
     * getShipToState
     *
     * @return string
     */
    public function getShipToState()
    {
        return $this->getResponseParameter('ship_to_state');
    }

    /**
     * getShipToZipcode
     *
     * @return string
     */
    public function getShipToZipcode()
    {
        return $this->getResponseParameter('ship_to_zipcode');
    }

    /**
     * getShipToCountry
     *
     * @return string
     */
    public function getShipToCountry()
    {
        return $this->getResponseParameter('ship_to_country');
    }

    /**
     * getTax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->getResponseParameter('tax');
    }

    /**
     * getDuty
     *
     * @return string
     */
    public function getDuty()
    {
        return $this->getResponseParameter('duty');
    }

    /**
     * getFreight
     *
     * @return string
     */
    public function getFreight()
    {
        return $this->getResponseParameter('freight');
    }

    /**
     * getTaxExempt
     *
     * @return string
     */
    public function getTaxExempt()
    {
        return $this->getResponseParameter('tax_exempt');
    }

    /**
     * getPurchaseOrder
     *
     * @return string
     */
    public function getPurchaseOrder()
    {
        return $this->getResponseParameter('purchase_order');
    }

    /**
     * getMd5Hash
     *
     * @return string
     */
    public function getMd5Hash()
    {
        return $this->getResponseParameter('md5_hash');
    }

    /**
     * getCardCodeResponse
     *
     * @return string
     */
    public function getCardCodeResponse()
    {
        return $this->getResponseParameter('card_code_response');
    }

    /**
     * getCardholderAuthenticationVerificationResponse
     *
     * @return string
     */
    public function getCardholderAuthenticationVerificationResponse()
    {
        return $this->getResponseParameter('cardholder_authentication_verification_response');
    }

    /**
     * getResponseParameter
     *
     * @return string
     */
    public function getResponseParameter($key)
    {
        return isset($this->responseArray[$key]) ? $this->responseArray[$key] : null;
    }

    /**
     * getResponseParameter
     *
     * @return array
     */
    public function getResponseArray()
    {
        return $this->responseArray;
    }

    /**
     * isSuccess
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getResponseCode() == 1;
    }
    /**
     * isSuccess
     *
     * @return bool
     */
    public function isDeclined()
    {
        return $this->getResponseCode() == 2;
    }

    /**
     * isSuccess
     *
     * @return bool
     */
    public function isError()
    {
        return $this->getResponseCode() < 2;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getResponseReasonText();
    }

}
