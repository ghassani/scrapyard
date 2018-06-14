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

use Spliced\Component\Commerce\Security\Authentication\Token\GoogleUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;

/**
 * GoogleAuthenticationListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleAuthenticationListener extends AbstractAuthenticationListener
{
    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager->authenticate(
            new GoogleUserToken('', array(), $request->query->get('code'))
        );
    }
}
