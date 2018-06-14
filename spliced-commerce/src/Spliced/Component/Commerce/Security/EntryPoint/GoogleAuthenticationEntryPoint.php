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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Spliced\Component\Commerce\Security\Authentication\Client\GoogleOAuth2Client as OAuth2Client;

/**
 * GoogleAuthenticationEntryPoint
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $google;
    protected $options;
    protected $permissions;

    /**
     * Constructor
     *
     * @param OAuth2Client $google
     * @param array        $options
     * @param array        $permissions
     */
    public function __construct(OAuth2Client $google, array $options = array(), array $permissions = array())
    {
        $this->google = $google;
        $this->permissions = $permissions;
        $this->options = new ParameterBag($options);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        
        return new RedirectResponse($this->google->getLoginUrl());
       /*
        if ($this->options->get('server_url') && $this->options->get('app_url')) {
            return new Response('<html><head></head><body><script>top.location.href="'.$loginUrl.'";</script></body></html>');
        }

        return new RedirectResponse($loginUrl);*/
    }
}
