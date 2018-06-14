<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Twig\Extension;

/**
 * GeneratorExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GeneratorExtension extends \Twig_Extension
{


    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'is_array' => new \Twig_Function_Method($this, 'is_array'),
            'is_object' => new \Twig_Function_Method($this, 'is_object'),
            'array_to_string_array' => new \Twig_Function_Method($this, 'array_to_string_array'),
        );
    }
    
    
    /**
     * is_array
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function array_to_string_array($value, $preserveKeys = true, $inline = true)
    {
        if(!is_array($value)){
            return '';
        }
        
        $return = "array(";
        
        $unpreservedKey = 0;
        foreach($value as $currentKey => $currentValue) {
            if(is_array($currentValue)){
                $return .= "'".($preserveKeys ? $currentKey : $unpreservedKey)."' => ".$this->array_to_string_array($currentValue, $preserveKeys, true).",";
            } else {
                $return .= "'".($preserveKeys ? $currentKey : $unpreservedKey)."' => '".$currentValue."',";
            }
            ++$unpreservedKey;
        }
        
        $return .= ')';
        
        $return .= $inline ? '' : ';';

        return $return;
    }
    
    /**
     * is_array
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function is_array($value)
    {
        return is_array($value);
    }

    /**
     * is_object
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function is_object($value)
    {
        return is_object($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_admin_generator';
    }

}
