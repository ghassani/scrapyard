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
 * CartInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CartInterface
{
    /**
     * getCustomer
     *
     * @return CustomerInterface|null
     */
    public function getCustomer();
    
    /**
     * getVisitor
     *
     * @return VisitorInterface
     */
    public function getVisitor();
    
    /**
     * getItems
     *
     * @return array Collection
     */
    public function getItems();
    
    
    public function getCreatedAt();
}
    