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

use Symfony\Component\BrowserKit\Request as BaseRequest;

/**
 * Request
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Request extends BaseRequest
{

    protected $parameters = array ();

    /**
     * @param array  $parameters
     *
     * {@inheritDoc}
     */
    public function __construct($url, $requestType, array $parameters = array())
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        
        $returnUrlKeyParameter = 'CCRURL';
        if($requestType == Client::REQUEST_ACH){
            $returnUrlKeyParameter = 'CKRURL';
        }
        
        if(!isset($this->parameters[$returnUrlKeyParameter])){
            $this->parameters[$returnUrlKeyParameter] = null;
        }
        
        parent::__construct($url, 'POST', $this->parameters);
    }

    /**
     * setName
     *
     * @param string $value
     */
    public function setName($value)
    {
        return $this->setParameter('NameonAccount',$value);
    }


    /**
     * setAddress
     *
     * @param string $value
     */
    public function setAddress($value)
    {
        return $this->setParameter('AVSADDR',$value);
    }
    
    /**
     * setZipcode
     *
     * @param string $value
     */
    public function setZipcode($value)
    {
        return $this->setParameter('AVSZIP',$value);
    }
    
    /**
     * setTax
     *
     * @param string $value
     */
    public function setTax($value)
    {
        return $this->setParameter('TaxAmount',$value);
    }
    
    /**
     * setTaxIndicator
     *
     * @param string $value
     */
    public function setTaxIndicator($value)
    {
        if(!in_array($value, array(0,1,2))){
            throw new \InvalidArgumentException('Tax Indicator must be 0-2.');
        }
        return $this->setParameter('TaxIndicator',$value);
    }
    
    /**
     * setAmount
     *
     * @param string $value
     */
    public function setAmount($value)
    {
        return $this->setParameter('Amount',$value);
    }
    
    /**
     * setCreditAmount
     *
     * @param string $value
     */
    public function setCreditAmount($value)
    {
        return $this->setParameter('CreditAmount',$value);
    }
    
    /**
     * setInvoiceNumber
     *
     * @param string $value
     */
    public function setInvoiceNumber($value)
    {
        return $this->setParameter('PONumber',$value);
    }

    /**
     * setRefId
     *
     * @param string $value
     */
    public function setRefId($value)
    {
        return $this->setParameter('RefID',$value);
    }

    /**
     * setCvv2
     *
     * @param string $value
     */
    public function setCvv2($value)
    {
        return $this->setParameter('CVV2',$value);
    }

    /**
     * setExpirationDasetExpirationMonthte
     *
     * @param string $value
     */
    public function setExpirationMonth($value)
    {
        return $this->setParameter('CCMonth', $value);
    }
    
    /**
     * setExpirationYear
     *
     * @param string $value
     */
    public function setExpirationYear($value)
    {
        return $this->setParameter('CCYear', $value);
    }
    
    /**
     * setCreditCard
     * 
     * Proxy method for setAccountNo
     * 
     * @param string $value
     */
    public function setCreditCard($value)
    {
        return $this->setAccountNo($value);
    }

    /**
     * setAccountNo
     * 
     * Credit Card Number for Credit Transactions or Bank Account Number for ACH
     */
    public function setAccountNo($value)
    {
        return $this->setParameter('AccountNo',$value);
    }
    
    /**
     * setDescription
     * 
     * Description for ACH Transactions
     */
    public function setDescription($value)
    {
        return $this->setParameter('DESCRIPTION',$value);
    }
    
    /**
     * setDescDate
     * 
     * For ACH Transactions Only. Must be MMDDYY format, pass any valid date string or unix time
     */
    public function setDescDate($value)
    {
        $date = new \DateTime($value);
        return $this->setParameter('DESCDATE',$date->format('MDy'));
    }
    
    /**
     * setTransType
     * 
     * @param string $value - CK or SK are the only valid values
     * 
     * The Transaction Type for ACH Transactions
     */
    public function setTransType($value)
    {
        if(!in_array($value, array('CK','SA'))){
            throw new \InvalidArgumentException(sprintf('Transaction Type %s is Invalid. Must be CK for Checking or SA for Savings', $value));
        }
        return $this->setParameter('TRANSTYPE',$value);
    }
    
    /**
     * setTransRoute
     * 
     * The routing number for ACH Transactions
     */
    public function setTransRoute($value)
    {
        return $this->setParameter('TRANSROUTE',$value);
    }
    
    /**
     * setUser1
     * 
     * Set user field data
     */
    public function setUser1($value)
    {
        return $this->setParameter('USER1',$value);
    }
    
    /**
     * setUser2
     * 
     * Set user field data
     */
    public function setUser2($value)
    {
        return $this->setParameter('USER2',$value);
    }
    
    /**
     * setUser3
     * 
     * Set user field data
     */
    public function setUser3($value)
    {
        return $this->setParameter('USER3',$value);
    }
    
    /**
     * setUser4
     * 
     * Set user field data
     */
    public function setUser4($value)
    {
        return $this->setParameter('USER4',$value);
    }
    
    
    /**
     * setParameter
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * getParameters
     * 
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

}
