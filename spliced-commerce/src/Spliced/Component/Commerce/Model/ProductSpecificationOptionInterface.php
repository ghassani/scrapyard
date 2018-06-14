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
 * ProductSpecificationOptionInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ProductSpecificationOptionInterface
{
	
    /** 
     * Option type which represents a single option and single value  
     */
    const OPTION_TYPE_SINGLE_VALUE = 1;
    
    /** 
     * Option type which represents a single option with multiple values 
     */
    const OPTION_TYPE_MULTIPLE_VALUE = 2;
    
    /**
     * Option type which represents a single option with a variable/custom value
     */
    const OPTION_TYPE_CUSTOM_VALUES = 3;
}
