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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ProductSpecificationOptionValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ProductSpecificationOptionValue
{
    
     /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="value", type="string", unique=false, length=255, nullable=false)
     */
    protected $value;

    /**
     * @ORM\Column(name="public_value", type="string", unique=false, length=255, nullable=true)
     */
    protected $publicValue;
    
    /**
     * @ORM\Column(name="position", type="integer", unique=false, nullable=true)
     */
    protected $position;
    
    /**
     * @ORM\ManyToOne(targetEntity="ProductSpecificationOption", inversedBy="values")
     * @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     */
    protected $option;

    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set publicValue
     *
     * @param text $publicValue
     */
    public function setPublicValue($publicValue)
    {
        $this->publicValue = $publicValue;

        return $this;
    }

    /**
     * Get publicValue
     *
     * @return text
     */
    public function getPublicValue()
    {
        return $this->publicValue ? $this->publicValue : $this->value;
    }

    /**
     * setPosition
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * getPosition
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
    

    /**
     * getOption
     *
     * @return ProductSpecificationOptionInterface
    */
    public function getOption()
    {
    	return $this->option;
    }

    /**
     * setOption
     *
     * @param ProductSpecificationOptionInterface option
     *
     * @return self
    */
    public function setOption(ProductSpecificationOptionInterface $option)
    {
	    $this->option = $option;
	    return $this;
    }
    
}
