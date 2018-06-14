<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * FacebookSecurityController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookSecurityController extends Controller
{

    /**
     *
     */
    public function loginAction()
    {
        return $this->get('commerce.security.authentication.entry_point.facebook.main')->start($this->getRequest());

    }

    /**
     *
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

}
