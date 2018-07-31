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

use Symfony\Component\BrowserKit\Request as BaseRequest;

/**
 * Request
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Request extends BaseRequest
{
    /**
     * @param string $transactionType
     * @param string $transactionMethod
     * @param array  $parameters
     *
     * {@inheritDoc}
     */
    public function __construct($uri, $transactionType, $tenderType, array $parameters = array())
    {
        parent::__construct($uri, 'POST', array_merge($parameters,array(
            'TRXTYPE' => $transactionType,
            'TENDER' => $tenderType,
        )));
    }

    /**
     * setParameter
     *
     * @param string $key
     * @param string $value
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * setCreditCardNumber
     */
    public function setCreditCard($value)
    {
        return $this->setParameter('ACCT',$value);
    }

    /**
     * setExpirationDate
     */
    public function setExpirationDate($value)
    {
        return $this->setParameter('EXPDATE',$value);
    }

    /**
     * setCvv2
     */
    public function setCvv2($value)
    {
        return $this->setParameter('CVV2',$value);
    }

    /**
     * setEmail
     */
    public function setEmail($value)
    {
        return $this->setParameter('EMAIL',$value);
    }

    /**
     * setFirstName
     */
    public function setFirstName($value)
    {
        return $this->setParameter('FIRSTNAME',$value);
    }

    /**
     * setMiddleName
     */
    public function setMiddleName($value)
    {
        return $this->setParameter('MIDDLENAME',$value);
    }

    /**
     * setLastName
     */
    public function setLastName($value)
    {
        return $this->setParameter('LASTNAME',$value);
    }

    /**
     * setStreet
     */
    public function setStreet($value)
    {
        return $this->setParameter('STREET',$value);
    }

    /**
     * setCity
     */
    public function setCity($value)
    {
        return $this->setParameter('CITY',$value);
    }

    /**
     * setState
     */
    public function setState($value)
    {
        return $this->setParameter('STATE',$value);
    }

    /**
     * setZip
     */
    public function setZip($value)
    {
        return $this->setParameter('ZIP',$value);
    }

    /**
     * setAmount
     */
    public function setAmount($value)
    {
        return $this->setParameter('AMT',$value);
    }

    /**
     * setCurrencyCode
     */
    public function setCurrencyCode($value)
    {
        return $this->setParameter('CURRENCYCODE',$value);
    }

    /**
     * setInvoiceNumber
     */
    public function setInvoiceNumber($value)
    {
        return $this->setParameter('INVNUM',$value);
    }

    /**
     * setClientIp
     */
    public function setClientIp($value)
    {
        return $this->setParameter('CLIENTIP',$value);
    }

    /**
     * setVerbosity
     */
    public function setVerbosity($value)
    {
        return $this->setParameter('VERBOSITY',$value);
    }

    /**
     * setComment1
     */
    public function setComment1($value)
    {
        return $this->setParameter('COMMENT1',$value);
    }

    /**
     * setSku
     */
    public function setSku($value)
    {
        return $this->setParameter('SKU',$value);
    }

    /**
     * setRecurring
     */
    public function setRecurring($value)
    {
        return $this->setParameter('RECURRING',$value);
    }

    /**
     * setTaxAmount
     */
    public function setTaxAmount($value)
    {
        return $this->setParameter('TAXAMT',$value);
    }

    /**
     * setPoNumber
     */
    public function setPoNumber($value)
    {
        return $this->setParameter('PONUM',$value);
    }

    /**
     * setShipToFirstName
     */
    public function setShipToFirstName($value)
    {
        return $this->setParameter('SHIPTOFIRSTNAME',$value);
    }

    /**
     * setShipToLastName
     */
    public function setShipToLastName($value)
    {
        return $this->setParameter('SHIPTOLASTNAME',$value);
    }

    /**
     * setShipToStreet
     */
    public function setShipToStreet($value)
    {
        return $this->setParameter('SHIPTOSTREET',$value);
    }

    /**
     * setShipToZip
     */
    public function setShipToZip($value)
    {
        return $this->setParameter('SHIPTOZIP',$value);
    }

    /**
     * setShipToState
     */
    public function setShipToState($value)
    {
        return $this->setParameter('SHIPTOSTATE',$value);
    }

    /**
     * setOriginalTransactionId
     */
    public function setOriginalTransactionId($value)
    {
        return $this->setField('ORIGID',$value);
    }

}
