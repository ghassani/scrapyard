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
 * ProductAttributeOption
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ProductAttributeOptionInterface
{
    
    /** 
     * Option type which represents a value collected by the user they are
     * able to type in
     */
    const OPTION_TYPE_USER_DATA_INPUT = 1;
    

    /**
     * Option type which represents a value collected by the user they are
     * able to type select from a set of choices
     *
     * Can (optionally) alter price of product up or down
     */
    const OPTION_TYPE_USER_DATA_SELECTION = 2;
}
