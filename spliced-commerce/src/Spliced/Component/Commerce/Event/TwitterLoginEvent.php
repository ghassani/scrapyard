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
 * TwitterLoginEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TwitterLoginEvent extends Event
{

    /**
     * Constructor
     *
     * @param AdvancedUserInterface $user
     * @param array                 $twitterProfile
     */
    public function __construct(AdvancedUserInterface &$user, array $twitterProfile = array())
    {
        $this->user = $user;
        $this->twitterProfile = $twitterProfile;
    }

    /**
     * AdvancedUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * getTwitterProfile
     *
     * @return array
     */
    public function getTwitterProfile()
    {
        return $this->twitterProfile;
    }
}
