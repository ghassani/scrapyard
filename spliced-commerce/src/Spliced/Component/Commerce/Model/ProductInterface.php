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
 * ProductInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ProductInterface
{
     /** Availability Constants */   
    const AVAILABILITY_IN_STOCK              = 1;
    const AVAILABILITY_OUT_OF_STOCK          = 2;
    const AVAILABILITY_BACKORDERED           = 3;
    const AVAILABILITY_CALL_TO_ORDER         = 4;
    const AVAILABILITY_BUILT_TO_ORDER        = 5;
    const AVAILABILITY_UNAVAILABLE           = 6;
    const AVAILABILITY_STORE_PICKUP          = 7;
    
    /** Type Constants */
    const TYPE_PHYSICAL      = 1;
    const TYPE_SERIALIZABLE  = 2;
    const TYPE_DIGITAL       = 3;
    const TYPE_DOWNLOAD      = 4;
    
    
    /**
     * getSku
     * 
     * @return string
     */
    public function getSku();

    
    /**
     * getSku
     * 
     * @return string
     */
    public function getName();

    /**
     * getCost
     * 
     * @return float
     */
    public function getCost();

    /**
     * getPrice
     * 
     * @return float
     */
    public function getPrice();

    /**
     * getAvailability
     * 
     * @return int
     */
    public function getAvailability();
}