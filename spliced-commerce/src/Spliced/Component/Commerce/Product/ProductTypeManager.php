<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Component\Commerce\Product\Type\TypeInterface;

/**
 * ProductTypeManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductTypeManager
{
    /** @var ArrayCollection */
	protected $types;
    
    /**
     * Constructor
	*/
    public function __construct()
    {
        $this->types = new ArrayCollection();
    }
    
	/**
	 * getHandlers
	 * 
	 * Returns a collection of registers type types
	 * 
	 * @return ArrayCollection
	*/
	public function getHandlers()
    {
        return $this->types;
    }
    
    /**
     * addType
     * 
     * Registers a type
     * 
     * @param TypeInterface $type
     */
	public function addType(TypeInterface $type)
	{
		$existingType = $this->getTypeByCode($type->getTypeCode());
         
		if($existingType !== false){
			throw new \InvalidArgumentException(sprintf('Product Type With Type Code %s Already Defined Under %s',
				$type->getTypeCode(),
                $existingType->getName()
			));
		}
        
		$existingType = $this->getTypeByName($type->getName());
         
		if($existingType !== false){
			throw new \InvalidArgumentException(sprintf('Product Type With Type Name %s Already Defined Under %s',
				$type->getName(),
                get_class($existingHandler)
			));
		}
        
		$this->types->set($type->getName(), $type);
        
        return $this;
     }
     
	/**
	 * hasName
	 * 
	 * @param string $name
	*/
	public function hasName($name)
	{
		foreach($this->types as $types){
			if($type->getName() === $name){
                return true;
            }
		}
        return false;
	}

	/**
	 * hasCode
	 *
	 * @param int $typeCode
	 */
	public function hasCode($typeCode)
	{
      
		$typeCode = (int) $typeCode;
      
      	foreach($this->types as $type){
      		if($type->getTypeCode() === $typeCode){
      			return true;
      		}
      	}
      
		return false;
	}
      
	/**
	 * getTypeByName
	 * 
	 * @param string $name
	*/
	public function getTypeByName($name)
	{
		foreach($this->types as $type){
			if($type->getName() === $name){
                return $type;
            }
		}
        return false;
	}

      
	/**
	 * getHandlerByTypeCode
	 * 
	 * @param int $typeCode
	*/
	public function getTypeByCode($typeCode)
	{
		foreach($this->types as $type){
			if($type->getTypeCode() === $typeCode){
                return $type;
            }
        }
        
        return false;
	}
}
