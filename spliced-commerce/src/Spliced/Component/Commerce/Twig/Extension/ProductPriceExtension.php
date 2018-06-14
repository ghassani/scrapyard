<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductTierPrice;
use Spliced\Component\Commerce\Model\CartItemInterface;
use Spliced\Component\Commerce\Product\ProductPriceHelper;

/**
 * ProductPriceExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
 class ProductPriceExtension extends \Twig_Extension
{
    
    /**
     * Constructor
     * 
     * @param ProductPriceHelper $priceHelper
     */
    public function __construct(ProductPriceHelper $priceHelper)
    {
        $this->priceHelper = $priceHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_product_price' => new \Twig_Function_Method($this, 'productPrice'),
            'commerce_product_price_total' => new \Twig_Function_Method($this, 'productPriceTotal'),
            'commerce_product_final_price' => new \Twig_Function_Method($this, 'productFinalPrice'),
            'commerce_product_tax' => new \Twig_Function_Method($this, 'productTax'),
            'commerce_product_tier_price_savings' => new \Twig_Function_Method($this, 'productTierPriceSavings'),
        );
    }

    /**
     *
     *
     */
    public function productAffiliatePrice(ProductInterface $product)
    {
        return $this->priceHelper->getAffiliatePrice($product);
    }

    /**
     *
     *
     */
    public function productTax(ProductInterface $product)
    {
        return $this->priceHelper->getTax($product);
    }

    /**
     *
     *
     */
    public function productFinalPrice(ProductInterface $product)
    {
        return $this->priceHelper->getFinalPrice($product);
    }

    /**
     *
     */
    public function productPrice(ProductInterface $product, $quantity = null, ProductTierPrice $tierData = null, CartItemInterface $cartItem = null)
    {
        return $this->priceHelper->getPrice($product, $quantity, $tierData, $cartItem);
    }

    /**
     *
     */
    public function productTierPriceSavings(ProductInterface $product, ProductTierPrice $tierData = null, $quantity = null)
    {
        return $this->priceHelper->getTieredSaving($product, $tierData, $quantity);
    }

    /**
     *
     */
    public function productPriceTotal(ProductInterface $product, $quantity = null, $includeTaxes = false, CartItemInterface $cartItem = null)
    {
        return $this->priceHelper->getPriceTotal($product, $quantity, $includeTaxes, $cartItem);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_product_price';
    }

}
