<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Breadcrumb;

/**
 * Breadcrumb
 *
 * Data structure representing a breadcrumb.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Breadcrumb implements \ArrayAccess
{
    protected $anchor;
    protected $title;
    protected $href;
    protected $position;

    /**
     * Constructor
     *
     * @param string $anchor
     * @param string $title
     * @param string $href
     * @param string $position
     */
    public function __construct($anchor, $title, $href, $position = null)
    {
        $this->anchor = $anchor;
        $this->title = $title;
        $this->href = $href;
        $this->position = $position;
    }

    /**
     * getAnchor
     *
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * setAnchor
     *
     * @param  string     $anchor
     * @return Breadcrumb
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;

        return $this;
    }

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * setTitle
     *
     * @param  string     $title
     * @return Breadcrumb
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * getHref
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * setHref
     *
     * @param  string     $href
     * @return Breadcrumb
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * getPosition
     *
     * @return int|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * setPosition
     *
     * @param  string     $position
     * @return Breadcrumb
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->$offset) ? $this->$offset : null;
    }
}
