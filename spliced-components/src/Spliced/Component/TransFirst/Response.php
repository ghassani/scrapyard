<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\TransFirst;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Response
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Response extends BaseResponse
{

    /**
     *
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
        parent::__construct($content,$status,$headers);

        
        parse_str(trim(strip_tags($content)), $this->responseArray);
    }


    /**
     * getAuthorizationCode
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getResponseParameter('TransID');
    }

    /**
     * getAvsResponse
     *
     * @return string
     */
    public function getAvsResponseMessage()
    {
        return $this->getResponseParameter('CVV2ResponseMsg');
    }
    
    /**
     * getAvsResponse
     *
     * @return string
     */
    public function getAvsResponse()
    {
        return $this->getResponseParameter('AVSCode');
    }


    /**
     * getInvoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->getResponseParameter('REFNO');
    }

    /**
     * getAmount
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->getResponseParameter('Notes');
    }
    
    /**
     * getDescription
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->getResponseParameter('Auth');
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
     * Returns true if we recieved a response, false otherwise.
     * Does not check to see if the transaction was charged or not, 
     * use isDeclined() or isApproved() to check for that
     * 
     * @return bool
     */
    public function isSuccess()
    {
       return $this->getTransactionId() ? true : false;
    }
    
    /**
     * isDeclined
     *
     * @return bool
     */
    public function isDeclined()
    {
        $authCode = strtolower($this->getAuth());
        if(empty($authCode) || $authCode === 'declined'){
            return true;
        }
        return false;
    }

    
    /**
     * isApproved
     *
     * @return bool
     */
    public function isApproved()
    {
        return !$this->isDeclined();
    }
    
    /**
     * getUser1
     * 
     * Get User Supplied Data
     */
    public function getUser1()
    {
        return $this->getResponseParameter('USER1');
    }
    
    /**
     * getUser2
     * 
     * Get User Supplied Data
     */
    public function getUser2()
    {
        return $this->getResponseParameter('USER2');
    }
    
    /**
     * getUser3
     * 
     * Get User Supplied Data
     */
    public function getUser3()
    {
        return $this->getResponseParameter('USER3');
    }
    
    /**
     * getUser4
     * 
     * Get User Supplied Data
     */
    public function getUser4()
    {
        return $this->getResponseParameter('USER4');
    }
    
}
