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
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * StringToArrayModelTransformer
 * 
 * Used for attributes and specifications. In case of single value options,
 * this transformer will create an array value for the model from a string
 *  (single selection) value.
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class StringToArrayModelTransformer implements  DataTransformerInterface
{    
    /**
     * {@inheritDoc}
     */
    public function transform($values)
    {
        
        if (null === $values) {
            return array();
        }
        
        if(!is_array($values)){
            $values = array($values);
        }
        
        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($values)
    {
        if(is_null($values)){
            $values = array();
        }
        
        if (!is_array($values)) {
            $values = array($values);
        }
        
        return $values;
    }
}        