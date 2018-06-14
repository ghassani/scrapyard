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

use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Product\ProductPriceHelper;

/**
 * CartExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartExtension extends \Twig_Extension
{

    protected $cartManager;

    public function __construct(CartManager $cartManager, ProductPriceHelper $priceHelper)
    {
       $this->cartManager = $cartManager;
       $this->priceHelper = $priceHelper;
    }

    /**
     * getPriceHelper
     * 
     * @return ProductPriceHelper
     */
    public function getPriceHelper()
    {
        return $this->priceHelper;
    }
    
    /**
     * getCartManager
     * 
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->cartManager;
    }
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(

        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_cart_contains' => new \Twig_Function_Method($this, 'cartContains'),
            'commerce_cart_quantity' => new \Twig_Function_Method($this, 'cartQuantity'),
            'commerce_cart_manager' => new \Twig_Function_Method($this, 'getCartManager'),     
            'commerce_cart_items' => new \Twig_Function_Method($this, 'getCartItems'),
            'commerce_cart_total' => new \Twig_Function_Method($this, 'cartTotal'),
            'commerce_cart_subtotal' => new \Twig_Function_Method($this, 'cartSubTotal'),
            'commerce_cart_tax' => new \Twig_Function_Method($this, 'cartTotalTax'),
        );
    }
    
    /**
     * getCartItems
     */
    public function getCartItems()
    {
        return $this->getCartManager()->getItems();
    }
    
    /**
     * cartContains
     *
     * Checks to see if a specific product is in the shopping cart
     *
     * @param ProductInterface $product
     */
    public function cartContains(ProductInterface $product)
    {
        return $this->getCartManager()->hasProduct($product);
    }

    /**
     *
     */
    public function cartQuantity(ProductInterface $product)
    {
        return $this->getCartManager()->getQuantity($product,0);
    }


    /**
     *
     */
    public function cartTotal()
    {
        return $this->getPriceHelper()->getCartTotal();
    }

    /**
     *
     */
    public function cartTierTotal()
    {
        return $this->getPriceHelper()->getCartTierTotal();
    }

    /**
     *
     */
    public function cartSubTotal()
    {
        return $this->getPriceHelper()->getCartSubTotal();
    }

    /**
     *
     */
    public function cartTierSubTotal()
    {
        return $this->getPriceHelper()->getCartTierTotal();
    }

    /**
     *
     */
    public function cartTotalTax()
    {
        return $this->getPriceHelper()->getCartTotalTax();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_cart';
    }

}
