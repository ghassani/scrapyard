<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * CustomerCreditCardInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CustomerCreditCardInterface
{
    const REGEXP_VALID_CREDIT_CARD = '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/';
    const REGEXP_VISA = '/^4[0-9]{12}(?:[0-9]{3})?$/';
    const REGEXP_MASTERCARD = '/^5[1-5][0-9]{14}$/';
    const REGEXP_DISCOVER = '/^6(?:011|5[0-9]{2})[0-9]{12}$/';
    const REGEXP_AMERICAN_EXPRESS = '/^3[47][0-9]{13}$/';
    const REGEXP_DINERS_CLUB = '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/';
    const REGEXP_JCB = '/^(?:2131|1800|35\d{3})\d{11}/';

}
