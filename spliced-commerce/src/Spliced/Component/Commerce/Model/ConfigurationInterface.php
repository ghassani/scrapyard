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
 * ConfigurationInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ConfigurationInterface
{
    /**
     * setKey
     *
     * @param string $key
     * 
     * @return self
     */
    public function setKey($key);
    
    /**
     * getKey
     *
     * @return string
     */
    public function getKey();
    
    /**
     * setValue
     *
     * @param mixed $value
     * 
     * @return self
     */
    public function setValue($value);
    
    /**
     * getValue
     *
     * @return text
     */
    public function getValue();
    
    /**
     * setType
     * 
     * @param string $type
     * 
     * @return self
     */
    public function setType($type);
    
    /**
     * getType
     * 
     * @return string
     */
    public function getType();
    
    /**
     * setLabel
     *
     * @param string $label
     * 
     * @return self
     */
    public function setLabel($label);
    
    /**
     * getLabel
     * 
     * @return string
    */
    public function getLabel();
    
    /**
     * setHelp
     *
     * @param string $help
     * 
     * @return self
     */
    public function setHelp($help);
    
    /**
     * getHelp
     * 
     * @return string
    */
    public function getHelp();
    
    /**
     * setGroup
     *
     * @param string $group
     * 
     * @return self
     */
    public function setGroup($group);
    
    /**
     * getGroup
     * 
     * @return string
    */
    public function getGroup();

    /**
     * setPosition
     *
     * @param int $position
     * 
     * @return self
     */
    public function setPosition($position);
    
    /**
     * getPosition
     * 
     * @return int
    */
    public function getPosition();
    
    /**
     * setCreatedAt
     * 
     * @param DateTime $createdAt
     * 
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt);
    
    /**
     * getCreatedAt
     * 
     * @return DateTime
     */
    public function getCreatedAt();
    
    /**
     * setUpdatedAt
     * 
     * @param DateTime $updatedAt
     * 
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt);
    
    /**
     * getUpdatedAt
     * 
     * @return DateTime
     */
    public function getUpdatedAt();
    
    /**
     * setRequired
     *
     * @param bool $required
     * 
     * @return self
     */
    public function setRequired($required);
    
    /**
     * getRequired
     * 
     * @return bool
    */
    public function getRequired();
}
