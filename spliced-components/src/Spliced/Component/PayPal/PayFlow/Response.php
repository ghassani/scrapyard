<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\PayPal\PayFlow;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Response
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Response extends BaseResponse
{
    public $responseCodes = array(
            1 => 'User authentication failed',
            2 => 'Invalid tender. Your merchant bank account does not support the following credit card type that was submitted.',
            3 => 'Invalid transaction type. Transaction type is not appropriate for this transaction. For example, you cannot credit an authorization-only transaction.',
            4 => 'Invalid amount',
            5 => 'Invalid merchant information. Processor does not recognize your merchant account information. Contact your bank account acquirer to resolve this problem.',
            7 => 'Field format error. Invalid information entered.',
            8 => 'Not a transaction server',
            9 => 'Too many parameters or invalid stream',
            10 => 'Too many line items',
            11 => 'Client time-out waiting for response',
            12 => 'Declined. Check the credit card number and transaction information to make sure they were entered correctly. If this does not resolve the problem, have the customer call the credit card issuer to resolve.',
            13 => 'Referral. Transaction was declined but could be approved with a verbal authorization from the bank that issued the card. Submit a manual Voice Authorization transaction and enter the verbal auth code.',
            19 => 'Original transaction ID not found. The transaction ID you entered for this transaction is not valid.',
            20 => 'Cannot find the customer reference number',
            22 => 'Invalid ABA number',
            23 => 'Invalid account number. Check credit card number and re-submit.',
            24 => 'Invalid expiration date. Check and re-submit.',
            25 => 'Transaction type not mapped to this host',
            26 => 'Invalid vendor account',
            27 => 'Insufficient partner permissions',
            28 => 'Insufficient user permissions',
            50 => 'Insufficient funds available',
            99 => 'General error',
            100 => 'Invalid transaction returned from host',
            101 => 'Time-out value too small',
            102 => 'Processor not available',
            103 => 'Error reading response from host',
            104 => 'Timeout waiting for processor response. Try your transaction again.',
            105 => 'Credit error. Make sure you have not already credited this transaction, or that this transaction ID is for a creditable transaction. (For example, you cannot credit an authorization.)',
            106 => 'Host not available',
            107 => 'Duplicate suppression time-out',
            108 => 'Void error. Make sure the transaction ID entered has not already been voided. If not, then look at the Transaction Detail screen for this transaction to see if it has settled. (The Batch field is set to a number greater than zero if the transaction has been settled). If the transaction has already settled, your only recourse is a reversal (credit a payment or submit a payment for a credit).',
            109 => 'Time-out waiting for host response',
            111 => 'Capture error. Only authorization transactions can be captured.',
            112 => 'Failed AVS check. Address and Zip code do not match. An authorization may still exist on the cardholder.s account.',
            113 => 'Cannot exceed sales cap. For ACH transactions only.',
            114 => 'CVV2 Mismatch. An authorization may still exist on the cardholder.s account.',
            1000 => 'Generic host error. This is a generic message returned by your credit card processor.',
    );
    /**
     * {@inheritDoc}
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
        parent::__construct($content,$status,$headers);

        $this->responseArray = parse_str($content);
    }

    /**
     * isError
     *
     * @return bool
     */
    public function isError()
    {
        return !$this->isSuccess();
    }

    /**
     * getError
     *
     * @return string
     */
    public function getError()
    {
        if ($this->isSuccess()) {
            return null;
        }

        return sprintf('%s - %s',$this->responseArray['RESULT'], $this->responseCodes[$this->responseArray['RESULT']]);
    }

    /**
     * isSuccess
     *
     * @return bool
     */
    public function isSuccess()
    {
        return !$this->responseArray['RESULT'];
    }

    /**
     * getErrorCode
     */
    public function getErrorCode()
    {
        return $this->responseArray['RESULT'];
    }

    /**
     * getAuthorizationCode
     */
    public function getAuthorizationCode()
    {
        return isset($this->responseArray['AUTHCODE']) ? $this->responseArray['AUTHCODE'] : null;
    }

    /**
     * getResponseMessage
     *
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->responseArray['RESPMSG'];
    }

    /**
     * getTransactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return isset($this->responseArray['PNREF']) ? $this->responseArray['PNREF'] : null;
    }

    /**
     * isDeclined
     *
     * @return bool
     */
    public function isDeclined()
    {
        if ($this->getErrorCode() == 12 || $this->getResponseMessage() == 'Declined') {
            return true;
        }

        return false;
    }

    /**
     * getAvsAddr
     *
     * @return string
     */
    public function getAvsAddr()
    {
        return isset($this->responseArray['AVSADDR']) ? $this->responseArray['AVSADDR'] : null;
    }

    /**
     * getAvsZip
     *
     * @return string
     */
    public function getAvsZip()
    {
        return isset($this->responseArray['AVSZIP']) ? $this->responseArray['AVSZIP'] : null;
    }

    /**
     * getCvv2Match
     *
     * @return string
     */
    public function getCvv2Match()
    {
        return isset($this->responseArray['CVV2MATCH']) ? $this->responseArray['CVV2MATCH'] : null;
    }

    /**
     * getResponseCodeMessage
     *
     * @return string
     */
    public function getResponseCodeMessage()
    {
        if(!$this->getErrorCode())

            return null;

        return isset($this->responseCodes[$this->getErrorCode()]) ? $this->responseCodes[$this->getErrorCode()] : null;
    }
}
