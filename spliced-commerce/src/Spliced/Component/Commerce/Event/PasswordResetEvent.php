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

use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * PasswordResetEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PasswordResetEvent extends Event
{
    
    /**
     * 
     */
    public function __construct(CustomerInterface $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }
    
    /**
     *
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }
    
    /**
     * 
     */
    public function getUser()
    {
        return $this->user;
    }
}
