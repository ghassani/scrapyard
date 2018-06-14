<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\EntryPoint;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Spliced\Component\Commerce\Security\Authentication\Client\PayPalOAuth2Client as OAuth2Client;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * PayPalAuthenticationEntryPoint
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    /* @var OAuth2Client */
    protected $paypal;
    /* @var array */
    protected $options;
    /* @var array */
    protected $permissions;

    /** 
     * Constructor
     *
     * @param OAuth2Client $paypal
     * @param array        $options
     */
    public function __construct(OAuth2Client $paypal, array $options = array(), array $permissions = array())
    {
        $this->paypal = $paypal;
        $this->permissions = $permissions;
        $this->options = new ParameterBag($options);
    }
    
    /**
     * getPaypal
     */
    protected function getPaypal()
    {
        return $this->paypal;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->getPaypal()->getLoginUrl());
    }
}
