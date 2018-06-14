<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Spliced\Component\Commerce\Controller\ServiceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\Cart\CartManager;

/**
 * CartServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartServiceController extends ServiceController
{

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * @Template("SplicedCommerceBundle:Cart:Blocks/side_block.html.twig")
     *
     */
    public function sideBlockAction()
    {
        return  array(
           'items' => array(),//$this->cartManager->getCartProducts(),
        );
    }

}
