<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Firewall;

use Spliced\Component\Commerce\Security\Authentication\Token\AdminUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;

/**
 * AdminAuthenticationListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AdminAuthenticationListener extends UsernamePasswordFormAuthenticationListener
{
    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {                
        return $this->authenticationManager->authenticate(new AdminUserToken($this->providerKey, '', array(), $request->get('access_token')));
    }
}
