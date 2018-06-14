<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Payment;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Component\Commerce\Payment\Model\CreditCardPaymentProviderInterface;
use Spliced\Component\Commerce\Payment\Model\RemotelyProcessedPaymentProviderInterface;
use Spliced\Component\Commerce\Payment\Model\PaymentProviderInterface;
use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;
use Spliced\Component\Commerce\Cart\CartManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * PaymentManager
 *
 * This class handles the registration and fetching of all payment methods.
 * During bundle compilation, services which implement the class:
 * 
 * Spliced\Component\Commerce\Payment\Model\PaymentProviderInterface
 * 
 * and are tagged with commerce.payment_provider will be automatically 
 * registered.
 * 
 * In addition to the standard PaymentProviderInterface, you can also use:
 * 
 * Spliced\Component\Commerce\Payment\Model\CreditCardPaymentProviderInterface
 * Spliced\Component\Commerce\Payment\Model\RemotelyProcessedPaymentProviderInterface
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PaymentManager
{
    /** @var Collection $providers  */
    protected $providers;

    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param EncryptionManager $encryptionManager
     * @param CartManager $cartManager
     * @param OrderHelper $orderHelper
     * @param RouterInterface $router
     */
    public function __construct(ConfigurationManager $configurationManager, EncryptionManager $encryptionManager, CartManager $cartManager, OrderHelper $orderHelper, RouterInterface $router)
    {
        $this->providers = new PaymentProviderCollection();
        $this->configurationManager = $configurationManager;
        $this->encryptionManager = $encryptionManager;
        $this->cartManager = $cartManager;
        $this->orderHelper = $orderHelper;
        $this->router = $router;
    }
    
    /**
     * addProvider
     * 
     * @param PaymentProviderInterface $provider
     */
    public function addProvider(PaymentProviderInterface $provider)
    {
        $this->providers->add($provider);
        return $this;
    }
    
    /**
     * getRouter
     * 
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }
    
    
    /**
     * getProviders
     *
     * @return PaymentProviderCollection
     */
    public function getProviders($includeDisabled = false)
    {
        $providers = array();
        foreach($this->providers as $provider) {
            if(!$provider->getOption('enabled') && $includeDisabled){
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            } else if($provider->getOption('enabled',false)) {
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            }
        }
        ksort($providers);
        return new PaymentProviderCollection($providers);
    }
    
    /**
     * getCreditCardProviders
     *
     * @return PaymentProviderCollection
     */
    public function getCreditCardProviders($includeDisabled = false)
    {
        $providers = array();
        foreach($this->providers as $provider) {
            if(!$provider->acceptsCreditCards()){
                continue;
            }
            if(!$provider->getOption('enabled',false) && $includeDisabled){
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            } else if($provider->getOption('enabled',false)) {
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            }
        }
        ksort($providers);
        return new PaymentProviderCollection($providers);
    }
    
    /**
     * getRemotelyProcessedProviders
     *
     * @return PaymentProviderCollection
     */
    public function getRemotelyProcessedProviders($includeDisabled = false)
    {
        $providers = array();
        foreach($this->providers as $provider) {
            if(!$provider->isRemotelyProcessed()){
                continue;
            }
            if(!$provider->getOption('enabled', false) && $includeDisabled){
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            } else if($provider->getOption('enabled', false)) {
                $providers[sprintf('%s_%s',$provider->getOption('position', 0), $provider->getName())] = $provider;
            }
        }
        ksort($providers);
        return new PaymentProviderCollection($providers);
    }
     
    /**
     * getProvider
     * @param  string $name
     * @return PaymentProviderInterface
     */
    public function getProvider($name)
    {
        foreach($this->getProviders(true) as $provider){
            if(strtolower($provider->getName()) == strtolower($name)){
                return $provider;
            }
        }
        
        throw new \Exception(sprintf('Payment Provider %s Does Not Exist',$name));
    }

}