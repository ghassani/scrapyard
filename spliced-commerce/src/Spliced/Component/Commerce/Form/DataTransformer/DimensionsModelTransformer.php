<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * DimensionsModelTransformer
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class DimensionsModelTransformer implements  DataTransformerInterface
{    
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
    	if(is_null($value)){
    		return $value;
    	}
    	
    	$parts = explode('X', $value);

    	$return = array();
    	foreach(array('length','width', 'height') as $key => $newKey){
    		if(isset($parts[$key])){
    			$return[$newKey] = $parts[$key];
    		}
    	}
        
        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($values)
    {
        if(is_array($values) && strlen(implode('', $values)) === 0 || is_null($values)){
            return null;
        }
        return implode('X', $values);
    }
}     