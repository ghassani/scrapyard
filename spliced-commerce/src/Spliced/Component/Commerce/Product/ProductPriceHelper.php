<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductTierPrice;
use Spliced\Component\Commerce\Model\CartItemInterface;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Utility\Math\BCMath;

/**
 * ProductPriceHelper
 * 
 * Handles the calculations of product prices with factors
 * like tiered pricing, special pricing, bundled/child item,e tc
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductPriceHelper
{
    /** Add Fixed Per Item - Tier Data field adjustment
     * will represent how much to add to each item price */
    const ADJUSTMENT_ADD_FIXED_PER_ITEM             = 1;
    
    /** Subtract Fixed Per Item - Tier Data field adjustment
     * will represent how much to subtract to each item price */
    const ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM         = 2;    
    
    /** Subtract Percentage Per Item - Tier Data field adjustment
     * will represent a percentage to subtract from each item price */
    const ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM   = 3;
    
    /** Add Percentage Per Item - Tier Data field adjustment
     * will represent a percentage to add to each item price */
    const ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM        = 4;
    /** Fixed Per Item - Tier Data field adjustment
     * will represent each item price */
    const ADJUSTMENT_FIXED_PER_ITEM                 = 5;

    /** Value to used when a min/max quantity is -1, representing unlimited. Expressed as PHP max integer size */
    const ADJUSTMENT_UNLIMITED_VALUE = PHP_INT_MAX;
    
    /** Percicion for calucaltions */
    const BCSCALE_DEFAULT = 4;

    /** Zero Value */
    const ZERO_VALUE = 00.00;

    /**
     * Constructor
     * 
     * @param CartManager          $cartManager
     * @param CheckoutManager      $checkoutManager
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(CartManager $cartManager, ConfigurationManager $configurationManager)
    {
        $this->cartManager = $cartManager;
        $this->configurationManager = $configurationManager;
        $this->calculator = new BCMath($configurationManager->get('commerce.sales.calculation_precision',static::BCSCALE_DEFAULT));
    }

    /**
     * getCheckoutManager
     *
     * @return CheckoutManager
     */
    private function getCartManager()
    {
        return $this->cartManager;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    private function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getBasePrice
     *
     * Calculates the base price of the product. This will
     * only be different from the database set base price
     * if there is a valid special price
     *
     * @param ProductInterface $product
     *
     * @return float
     */
    public function getBasePrice(ProductInterface $product)
    {
    	if(!$product->hasSpecialPrice()){
    		return $this->calculator->add($product->getPrice(), static::ZERO_VALUE);
    	}
    	
        return $this->calculator->add($product->getSpecialPrice(), static::ZERO_VALUE);
    }

    /**
     * getPrice
     *
     * Calculates the base price of the product
     *
     * @param ProductInterface          $product
     * @param int                       $quantity - Will effects only Tier Price calculation result
     * @param ProductTierPrice|null $tierPrice - Optionally specify a certain tier data
     * @param CartItemInterface|null $cartItem - Optionally specify a cart item
     */
    public function getPrice(ProductInterface $product, $quantity = null, ProductTierPrice $tierPrice = null, CartItemInterface $cartItem = null)
    {

        $basePrice = $this->getBasePrice($product);
        $return = $basePrice;

        if(is_null($quantity)) {
            $quantity = $cartItem ? $cartItem->getQuantity() : $this->getCartManager()->getQuantity($product);
        }
                
        // handle tiered pricing, first checking if this is a bundled item 
        // and allowing tiered pricing to be applied
        if (is_null($tierPrice)) {
            if(!$cartItem){
                $tierPrice = $this->getMatchedTierPrice($product, $quantity);
            } else if($cartItem->allowTierPricing()){
                $tierPrice = $this->getMatchedTierPrice($product, $quantity);
            }             
        }
        
        if (!is_null($tierPrice) && (!$cartItem || $cartItem->allowTierPricing())) {
            if ($tierPrice instanceof ProductTierPrice) {
                $return = $this->doAdjustment($basePrice, $tierPrice->getAdjustmentType(), $tierPrice->getAdjustment());
                                
                $tierOptions = $tierPrice->getOptions();
     
                if(isset($tierOptions['round_to_next_dollar']) && $tierOptions['round_to_next_dollar']){
                    $return = ceil($return) - .01;
                }
            }
        }
        
        if($cartItem){
            $cartItemData = $cartItem->getItemData();

            // handle item price adjustments if this item has any specified
            if($cartItem->getPriceAdjustmentType() && 0 <= $cartItem->getPriceAdjustment()){
                $return = $this->doAdjustment($return, $cartItem->getPriceAdjustmentType(), $cartItem->getPriceAdjustment());
            }
            
            if($product->hasPriceAlteringAttributes()){
                foreach($product->getPriceAlteringAttributes() as $attribute){
                    $userSelection = isset($cartItemData['user_data'][$attribute->getOption()->getName()]) ?  
                        $cartItemData['user_data'][$attribute->getOption()->getName()] : null;
                        
                    if($userSelection){
                        foreach($attribute->getOption()->getValues() as $value){
                            if($value->getId() == $userSelection && $value->getPriceAdjustmentType()){
                                $return = $this->doAdjustment($return, $value->getPriceAdjustmentType(), $value->getPriceAdjustment());
                            }
                        }
                    }
                }
            }
        }
        
        return $return;
    }

    /**
     * getPriceTotal
     *
     * Gets the total price, including taxes
     *
     * @param ProductInterface $product
     * @param int              $quantity
     * @param bool $includeTaxes
     * @return float
     */
    public function getPriceTotal(ProductInterface $product, $quantity = null, $includeTaxes = false, CartItemInterface $cartItem = null)
    {
        if (is_null($quantity)) {
            if($cartItem){
                $quantity = $cartItem->getQuantity();
            } else {
                $quantity = $this->getCartManager()->getQuantity($product);
            }
        }
        
        $taxes = true === $includeTaxes ? $this->getTax($product, $cartItem) : static::ZERO_VALUE;

        return $this->calculator->add( $this->calculator->multiply($this->getPrice($product, $quantity, null, $cartItem), $quantity), $taxes );
    }

    /**
     * getTax
     *
     * Get the tax amount for the product
     *
     * @param ProductInterface $product
     *
     * @return float
     */
    public function getTax(ProductInterface $product, CartItemInterface $cartItem = null)
    {
        $taxRate = $this->getConfigurationManager()->get('commerce.sales.tax_rate', static::ZERO_VALUE);
        
        if (!$product->getIsTaxable()) {
            return static::ZERO_VALUE;
        }
        
        $productPrice = $this->getPriceTotal($product, ($cartItem ? $cartItem->getQuantity() : null), null, $cartItem);
        
        return $this->calculator->multiply($productPrice, $taxRate);
    }

    /**
     * getTieredSaving
     *
     * @param ProductInterface          $product
     * @param ProductTierPrice $tierPrice
     * @param int                       $quantity
     *
     * @return float
     */
    public function getTieredSaving(ProductInterface $product, ProductTierPrice $tierPrice, $quantity = null)
    {
        return $this->doAdjustment($this->getPrice($product), $tierPrice->getAdjustmentType(), $tierPrice->getAdjustment(), true);
    }
    /**
     * getMatchedTierPrice
     *
     * Tries to match a product with its proper tier data
     * based on either the passed quantity, or the quantity
     * in the shopping cart if left null.
     *
     * @param ProductInterface $product
     * @param int|null         $quantity
     *
     * @return ProductTierPrice|false
     */
    public function getMatchedTierPrice(ProductInterface $product, $quantity = null)
    {
        if (is_null($quantity)) {
            $quantity = $this->getCartManager()->getQuantity($product);
        }

        foreach ($product->getTierPrices() as $tierPrice) {
            $minQuantity = $tierPrice->getMinQuantity() == -1 ? self::ADJUSTMENT_UNLIMITED_VALUE : $tierPrice->getMinQuantity();
            $maxQuantity = $tierPrice->getMaxQuantity() == -1 ? self::ADJUSTMENT_UNLIMITED_VALUE :  $tierPrice->getMaxQuantity();

            if ($minQuantity <= $quantity && $maxQuantity >= $quantity) {
                return $tierPrice;
            }
        }
                
        return false;
    }

    /**
     * getCartTotal
     *
     * @return float
     */
    public function getCartTotal($includeTaxes = true)
    {
        $total = static::ZERO_VALUE;
        
        $calculator = $this->calculator;
        $priceHelper = $this;

        $getItemsPrice = function($items, $includeTaxes) use(&$getItemsPrice, &$total, $calculator, $priceHelper){
            foreach($items as $item){
                $productPrice = $priceHelper->getPriceTotal($item->getProduct(), null, $includeTaxes, $item);
                
                
                $total = $calculator->add($total, $productPrice);

                if($item->hasChildren()){
                    $total = $getItemsPrice($item->getChildren(), $includeTaxes);
                }
            }
            return $total;
        };
        
        return $getItemsPrice($this->getCartManager()->getItems(), $includeTaxes);
    }

    /**
     * getCartSubTotal
     *
     * @return float
     */
    public function getCartSubTotal()
    {
        $total = static::ZERO_VALUE;
                
        $calculator = $this->calculator;
        $priceHelper = $this;
        
        $getItemsPrice = function($items) use(&$getItemsPrice, &$total, $calculator, $priceHelper){
            foreach($items as $item){
                $total = $calculator->add($total, $priceHelper->getPriceTotal($item->getProduct(), null, false, $item));
                if($item->hasChildren()){
                    $total = $getItemsPrice($item->getChildren());
                }
            }
            return $total;
        };
        
        return $getItemsPrice($this->getCartManager()->getItems());
    }

    /**
     * getCartTotalTax
     *
     * @param bool $includeChildren - Add in children items as well
     * @return float
     */
    public function getCartTotalTax($includeChildren = true)
    {

        $getItemsTax = function($items, $includeChildren = true) use(&$getItemsTax){
            $total = static::ZERO_VALUE;
            
            foreach($items as $item){
                $total = $this->calculator->add($total, $this->getTax($item->getProduct(), null, false, $item));
                if(true === $includeChildren && $item->hasChildren()){
                    $total = $this->calculator->add($total, $getItemsTax($item->getChildren(), $includeChildren));
                }
            }
            return $total;
        };

        return $getItemsTax($this->getCartManager()->getItems(), $includeChildren);
    }
    
    /**
     * doAdjustment
     * 
     * Calculates an adjustment based upon available adjust types
     * See constants of this class for available types
     * 
     * @param float $initialPrice
     * @param int $adjustmentType
     * @param float|int $adjustmentAmount
     * @param bool $returnSavings - Returns the amount saved/added to the price instead of the final result
     */
     public function doAdjustment($initialPrice, $adjustmentType, $adjustmentAmount, $returnSavings = false)
     {
        $savings = static::ZERO_VALUE;
        
        switch ($adjustmentType) {
            case static::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM:
                $percentage = $this->calculator->divide($adjustmentAmount,100);
                $savings = $this->calculator->multiply($initialPrice, $percentage);
                $return = $this->calculator->subtract($initialPrice, $savings);
                break;
            case static::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM:
                $percentage = $this->calculator->divide($adjustmentAmount,100);
                $savings = $this->calculator->multiply($initialPrice,$percentage);
                $return = $this->calculator->add($initialPrice, $savings);
                break;
            case static::ADJUSTMENT_ADD_FIXED_PER_ITEM:
                $savings = $adjustmentAmount;
                $return = $this->calculator->add($initialPrice, $savings);
                break;
            case static::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM:
                $savings = $adjustmentAmount;
                $return = $this->calculator->subtract($initialPrice, $savings);
                break;
            case static::ADJUSTMENT_FIXED_PER_ITEM:
                $savings = $initialPrice - $adjustmentAmount;
                $return = $adjustmentAmount;
                break;
            default:
                $return = $initialPrice;
                break;
        } 
        
        if(true === $returnSavings){
            return $savings;
        }
        
        return $return;
     }
}
