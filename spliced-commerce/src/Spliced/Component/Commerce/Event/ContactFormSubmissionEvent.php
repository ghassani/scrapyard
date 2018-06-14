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

use Spliced\Component\Commerce\Model\ContactMessageInterface;

/**
 * ContactFormSubmissionEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContactFormSubmissionEvent extends Event
{
    /** @var ContactMessageInterface */
    protected $contactMessage;
    
    /**
     * @param ContactMessageInterface $contactMessage
     */
    public function __construct(ContactMessageInterface $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }
    
    /**
     * getContactMessage
     * 
     * @return ContactMessageInterface
     */
    public function getContactMessage()
    {
        return $this->contactMessage;
    }
}