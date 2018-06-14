<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * PayPalLoginEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PayPalLoginEvent extends Event
{

    /**
     * Constructor
     *
     * @param AdvancedUserInterface $user
     * @param array                 $payPalProfile
     */
    public function __construct(AdvancedUserInterface &$user, array $payPalProfile = array())
    {
        $this->user = $user;
        $this->payPalProfile = $payPalProfile;
    }

    /**
     * AdvancedUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * getPayPalProfile
     *
     * @return array
     */
    public function getPayPalProfile()
    {
        return $this->payPalProfile;
    }
}
