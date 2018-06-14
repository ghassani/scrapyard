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
 * SearchTermInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface SearchTermInterface
{
    /**
     * getSearchQuery
     * 
     * @return string
     */
    public function getSearchQuery();
    
    /**
     * setSearchQuery
     * 
     * @param string $searchQuery
     * 
     * @return SearchTermInterface
     */
    public function setSearchQuery($searchQuery);
    
    /**
     * getCreatedAt
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * setCreatedAt
     *
     * @param DateTime $dateTime
     *
     * @return SearchTermInterface
     */
    public function setCreatedAt(\DateTime $dateTime);
    
    /**
     * Set customer
     *
     * @param CustomerInterface $createdAt
     * @return SearchTerm
     */
    public function setCustomer(CustomerInterface $customer);
    
    /**
     * Get customer
     *
     * @return CustomerInterface
     */
    public function getCustomer();
    
    /**
     * Set visitor
     *
     * @param VisitorInterface $visitor
     * @return SearchTerm
     */
    public function setVisitor(VisitorInterface $visitor);
    
    /**
     * Get visitor
     *
     * @return VisitorInterface
     */
    public function getVisitor();
}
