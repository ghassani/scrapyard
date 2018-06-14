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

use Spliced\Component\Commerce\Security\Authentication\Token\PayPalUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;

/**
 * PayPalAuthenticationListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalAuthenticationListener extends AbstractAuthenticationListener
{
    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager()->authenticate(
            new PayPalUserToken('', array(), $request->query->get('code'), null)
        );
    }
    
}
