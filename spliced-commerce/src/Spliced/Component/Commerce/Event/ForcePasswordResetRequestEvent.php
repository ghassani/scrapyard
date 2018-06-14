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
 * ForcePasswordResetRequestEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ForcePasswordResetRequestEvent extends Event
{
    
    /**
     * 
     */
    public function __construct(CustomerInterface $user)
    {
        $this->user = $user;
    }
    
    
    /**
     * 
     */
    public function getUser()
    {
        return $this->user;
    }
}
