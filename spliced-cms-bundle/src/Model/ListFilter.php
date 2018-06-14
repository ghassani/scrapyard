<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Model;

/**
 * ListFilter
 *
 * Data structure for a query to use for filtering in lists
 */
class ListFilter implements \Serializable, \ArrayAccess
{
    
    protected $data;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset) 
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset) 
    {
        unset($this->data[$offset]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}