<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Spliced\Component\Commerce\Event\ProductViewEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * ProductEventListener
 * 
 * Frontend product event listener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductEventListener
{
    protected $securityContext;
    protected $om;
    
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param SecurityContext      $securityContext
     * @param Session              $session
     */
    public function __construct(ConfigurationManager $configurationManager, SecurityContext $securityContext, Session $session)
    {
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->configurationManager = $configurationManager;
    }

    /**
     * onProductView
     *
     * @param ProductViewEvent $event
     */
    public function onProductView(ProductViewEvent $event)
    {
        
    }
    
}