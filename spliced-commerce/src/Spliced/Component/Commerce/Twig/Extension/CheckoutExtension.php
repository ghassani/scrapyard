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

use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Spliced\Component\Commerce\Checkout\CheckoutNotifierManager;
use Spliced\Component\Commerce\Model\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CheckoutExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutExtension extends \Twig_Extension
{

    public function __construct(ContainerInterface $container)
    {
       $this->container = $container;
    }

    /**
     * getCheckoutManager
     * 
     * @return CheckoutManager
     */
    public function getCheckoutManager()
    {
        return $this->container->get('commerce.checkout_manager');
    }

    /**
     * getCheckouNotifiertManager
     *
     * @return CheckoutNotifierManager
     */
    public function getCheckoutNotifierManager()
    {
        return $this->container->get('commerce.checkout_notifier_manager');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'commerce_checkout_manager' => new \Twig_Function_Method($this, 'getCheckoutManager'),
            'commerce_checkout_notifier_manager' => new \Twig_Function_Method($this, 'getCheckoutNotifierManager'),
            'commerce_checkout_notifier_render_head' => new \Twig_Function_Method($this, 'checkoutNotifierRenderHead'),
            'commerce_checkout_notifier_render_notifiers' => new \Twig_Function_Method($this, 'checkoutNotifierRenderNotifiers'),
            'commerce_checkout_notifier_render_body_end' => new \Twig_Function_Method($this, 'checkoutNotifierRenderBodyEnd'),
        );
    }
    
    /**
     * checkoutNotifierRenderHead
     * 
     * @param OrderInterface
     * 
     * @return string
     */
    public function checkoutNotifierRenderHead(OrderInterface $order)
    {
        $return = array();
        foreach($this->getCheckoutNotifierManager()->getNotifiers() as $notifier){
            $return[] = $notifier->renderHeadHtml($order);
        }
        return implode(PHP_EOL, $return);
    }
    
    /**
     * checkoutNotifierRenderNotifiers
     * 
     * @param OrderInterface
     * 
     * @return string
     */
    public function checkoutNotifierRenderNotifiers(OrderInterface $order)
    {
        $return = array();
        foreach($this->getCheckoutNotifierManager()->getNotifiers() as $notifier){
            $return[] = $notifier->renderTrackerHtml($order);
        }
        return implode(PHP_EOL, $return);
    }
    
    /**
     * checkoutNotifierRenderBodyEnd
     *
     * @param OrderInterface
     *
     * @return string
     */
    public function checkoutNotifierRenderBodyEnd(OrderInterface $order)
    {
        $return = array();
        foreach($this->getCheckoutNotifierManager()->getNotifiers() as $notifier){
            $return[] = $notifier->renderBodyEndHtml($order);
        }
        return implode(PHP_EOL, $return);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_checkout';
    }

}
