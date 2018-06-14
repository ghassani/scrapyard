<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ShippingManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShippingMethodCollection extends ArrayCollection
{
	/**
	 * toJSON
	 * 
	 * @param bool $returnArray - If true, returns an array instead of json string
	 */
	public function toJSON($returnArray = false)
	{
		$return = array();
		foreach ($this as $method) {
			$_method = array(
				'name' => $method->getName(),
				'provider' => $method->getProvider()->getName(),
				'full_name' => $method->getFullName(),
				'label' => $method->getLabel(),
				'label2' => $method->getLabel2(),
				'price' => $method->getPrice(),					
			);
			if ($method instanceof Model\ConfigurableShippingMethod) {	
				foreach($method->getOptions() as $key => $option){
					$_method[str_replace($method->getConfigPrefix().'.', '', $key)] = $option;	
				}
				
			}
			
			array_push($return, $_method);
		}	
		
		return $returnArray === true ? $return : json_encode($return);
	}
}
