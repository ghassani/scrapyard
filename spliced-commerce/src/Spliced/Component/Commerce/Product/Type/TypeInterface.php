<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product\Type;

/**
 * TypeInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface TypeInterface
{
    /**
     * getName
     * 
     * The name of the Type Handler. For example, 'serializable'
     * 
     * @return string
     */
    public function getName();
    
    /**
     * __toString
     * 
     * The label of the Type Handler. For example, 'Some Label Text'
     * 
     * @return string
     */
    public function getLabel();
    
    /**
     * getTypeCode
     * 
     * The integer constant representing this type
     * @return int
     */
    public function getTypeCode();
    
    /**
     * isShippable
     * 
     * Returns true if the type in question is a physical/shippable product type
     */
    public function isShippable();
    
    /**
     * isElectronicDelivery
     * 
     * Returns true if the type in question is an electronic delivery method other than download
     */
    public function isElectronicDelivery();
    
    /**
     * isDownloadable
     * 
     * Returns true if the type in question is a downloadable product type
     */
    public function isDownloadable();
}