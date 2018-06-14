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

/**
 * FacebookAuthenticationEntryPoint
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $facebook;
    protected $options;
    protected $permissions;

    /**
     * Constructor
     *
     * @param BaseFacebook $facebook
     * @param array        $options
     */
    public function __construct(\BaseFacebook $facebook, array $options = array(), array $permissions = array())
    {
        $this->facebook = $facebook;
        $this->permissions = $permissions;
        $this->options = new ParameterBag($options);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {

        $redirect_to_facebook = $this->options->get('redirect_to_facebook_login');
        if ($redirect_to_facebook == false) {
            $loginPath = $this->options->get('login_path');

            return new RedirectResponse($loginPath);
        }

        $redirect_uri = $request->getUriForPath($this->options->get('check_path', ''));
        if ($this->options->get('server_url') && $this->options->get('app_url')) {
            $redirect_uri = str_replace($this->options->get('server_url'), $this->options->get('app_url'), $redirect_uri);
        }

        $loginUrl = $this->facebook->getLoginUrl(
           array(
                'display' => $this->options->get('display', 'page'),
                'scope' => implode(',', $this->permissions),
                'redirect_uri' => $redirect_uri,
        ));

        if ($this->options->get('server_url') && $this->options->get('app_url')) {
            return new Response('<html><head></head><body><script>top.location.href="'.$loginUrl.'";</script></body></html>');
        }

        return new RedirectResponse($loginUrl);
    }
}
