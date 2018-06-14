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

use Spliced\Component\Commerce\Security\Authentication\Token\TwitterUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;

/**
 * TwitterAuthenticationListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterAuthenticationListener extends AbstractAuthenticationListener
{
    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager->authenticate(
            new TwitterUserToken(
                $request->query->get('oauth_token'), 
                $request->query->get('oauth_verifier')
            )
        );
    }
}
