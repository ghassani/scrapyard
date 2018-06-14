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
 * SearchTerm
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class SearchTerm implements SearchTermInterface
{

    protected $customer;
    
    protected $visitor;
    
    protected $searchQuery;

    protected $createdAt;
    

    
    /**
     * Set searchQuery
     *
     * @param string $searchQuery
     * @return SearchTerm
     */
    public function setSearchQuery($searchQuery)
    {
        $this->searchQuery = $searchQuery;
    
        return $this;
    }
    
    /**
     * Get searchQuery
     *
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SearchTerm
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set customer
     *
     * @param CustomerInterface $createdAt
     * @return SearchTerm
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * Get customer
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * Set visitor
     *
     * @param VisitorInterface $visitor
     * @return SearchTerm
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }
    
    /**
     * Get visitor
     *
     * @return VisitorInterface
     */
    public function getVisitor()
    {
        return $this->visitor;
    }
}