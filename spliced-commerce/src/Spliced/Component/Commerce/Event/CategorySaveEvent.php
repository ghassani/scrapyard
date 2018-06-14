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

use Spliced\Component\Commerce\Model\CategoryInterface;

/**
 * CategorySaveEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategorySaveEvent extends Event
{
    /** CategoryInterface */
    protected $category;

    /**
     * @param CategoryInterface $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category    = $category;
    }

    /**
     * getCategory
     *
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

}
