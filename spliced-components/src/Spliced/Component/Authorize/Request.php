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

use Symfony\Component\BrowserKit\Request as BaseRequest;

/**
 * Request
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Request extends BaseRequest
{
    /** DEFAULT CONSTANTS */
    const DELIM_DATA = 'TRUE';

    const DELIM_CHAR = '|';

    const VERSION = '3.1';

    /**
     * @param array $transactionTypes
     */
    protected $transactionTypes = array(
        'VOID',
        'CREDIT',
        'AUTH_CAPTURE',
        'AUTH_ONLY'
    );

    /**
     * @param array $transactionMethods
     */
    protected $transactionMethods = array('CC');
    
    
    protected $parameters = array ();

    /**
     * @param string $transactionType
     * @param string $transactionMethod
     * @param array  $parameters
     *
     * {@inheritDoc}
     */
    public function __construct($uri, $transactionType, $transactionMethod = 'CC', array $parameters = array())
    {
        if (!in_array($transactionType,$this->getTransactionTypes())) {
            throw new \InvalidArgumentException(sprintf('Transaction Type `%s` is not valid. Must be one of: %s',
                $transactionType,
                implode(',',$this->getTransactionTypes())
            ));
        } elseif (!in_array($transactionMethod,$this->getTransactionMethods())) {
            throw new \InvalidArgumentException(sprintf('Transaction Method `%s` is not valid. Must be one of: %s',
                $transactionType,
                implode(',',$this->getTransactionMethods())
            ));
        }
        parent::__construct($uri, 'POST', array_merge($parameters,array(
            'x_type' => $transactionType,
            'x_method' => $transactionMethod,
            'x_delim_data' => self::DELIM_DATA,
            'x_delim_char' => self::DELIM_CHAR,
            'x_version' => self::VERSION,
        )));
    }

    /**
     * setFirstName
     *
     * @param string $value
     */
    public function setFirstName($value)
    {
        return $this->setParameter('x_first_name',$value);
    }

    /**
     * setLastName
     *
     * @param string $value
     */
    public function setLastName($value)
    {
        return $this->setParameter('x_last_name',$value);
    }

    /**
     * setAddress
     *
     * @param string $value
     */
    public function setAddress($value)
    {
        return $this->setParameter('x_address',$value);
    }

    /**
     * setCity
     *
     * @param string $value
     */
    public function setCity($value)
    {
        return $this->setParameter('x_city',$value);
    }

    /**
     * setState
     *
     * @param string $value
     */
    public function setState($value)
    {
        return $this->setParameter('x_state',$value);
    }

    /**
     * setZipcode
     *
     * @param string $value
     */
    public function setZipcode($value)
    {
        return $this->setParameter('x_zip',$value);
    }

    /**
     * setAmount
     *
     * @param string $value
     */
    public function setAmount($value)
    {
        return $this->setParameter('x_amount',$value);
    }

    /**
     * setInvoiceNumber
     *
     * @param string $value
     */
    public function setInvoiceNumber($value)
    {
        return $this->setParameter('x_invoice_num',$value);
    }

    /**
     * setDescription
     *
     * @param string $value
     */
    public function setDescription($value)
    {
        return $this->setParameter('x_description',$value);
    }

    /**
     * setEmail
     *
     * @param string $value
     */
    public function setEmail($value)
    {
        return $this->setParameter('x_email',$value);
    }

    /**
     * setRecurringBilling
     *
     * @param string $value
     */
    public function setRecurringBilling($value)
    {
        return $this->setParameter('x_recurring_billing',$value);
    }

    /**
     * setCvv2
     *
     * @param string $value
     */
    public function setCvv2($value)
    {
        return $this->setParameter('x_card_code',$value);
    }

    /**
     * setExpirationDate
     *
     * @param string $value
     */
    public function setExpirationDate($value)
    {
        return $this->setParameter('x_exp_date',$value);
    }

    /**
     * setCreditCard
     *
     * @param string $value
     */
    public function setCreditCard($value)
    {
        return $this->setParameter('x_card_num',$value);
    }

    /**
     *
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     *
     */
    public function getTransactionTypes()
    {
        return $this->transactionTypes;
    }

    /**
     *
     */
    public function getTransactionMethods()
    {
        return $this->transactionMethods;
    }
}
