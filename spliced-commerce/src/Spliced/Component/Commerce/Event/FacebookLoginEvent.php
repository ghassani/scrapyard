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
 * FacebookLoginEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookLoginEvent extends Event
{

    /**
     * Constructor
     *
     * @param AdvancedUserInterface $user
     * @param array                 $facebookProfile
     */
    public function __construct(AdvancedUserInterface &$user, array $facebookProfile = array())
    {
        $this->user = $user;
        $this->facebookProfile = $facebookProfile;
    }

    /**
     * AdvancedUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * getFacebookProfile
     *
     * @return array
     */
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }
}
