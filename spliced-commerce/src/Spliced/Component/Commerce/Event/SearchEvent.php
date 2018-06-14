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


/**
 * SearchEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SearchEvent extends Event
{

    /**
     * @param string $queryString
     */
    public function __construct($queryString)
    {
        $this->queryString = $queryString;
    }

    /**
     * getQueryString
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * setQueryString
     * @param  string $queryString
     * @return SearchEvent
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

}
