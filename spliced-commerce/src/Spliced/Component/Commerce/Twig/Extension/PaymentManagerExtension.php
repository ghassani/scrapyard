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

use Spliced\Component\Commerce\Payment\PaymentManager;

/**
 * PaymentManagerExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
 class PaymentManagerExtension extends \Twig_Extension
{

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * getPaymentManager
     * 
     * @return PaymentManager
     */
    protected function getPaymentManager()
    {
        return $this->paymentManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'get_payment_provider' => new \Twig_Function_Method($this, 'getPaymentProvider'),
        );
    }

   /**
    * 
    */
    public function getPaymentProvider($name)
    {
        return $this->getPaymentManager()->getProvider($name);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_payment_manager';
    }

}
