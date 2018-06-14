<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * NewsletterSubscriberInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface NewsletterSubscriberInterface
{

    /**
     * getEmail
     * 
     * @return string
     */
    public function getEmail();
    
    /**
     * setEmail
     *
     * @param string $email
     */
    public function setEmail($email);
    
    /**
     * getCreatedAt
     *
     * @return DateTime
     */
    public function getCreatedAt();
    
    /**
     * setCreatedAt
     *
     * @param DateTime $createdAt
    */
    public function setCreatedAt(\DateTime $createdAt);
    

    /**
     * getUpdatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt();
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
    */
    public function setUpdatedAt(\DateTime $updatedAt);
}
