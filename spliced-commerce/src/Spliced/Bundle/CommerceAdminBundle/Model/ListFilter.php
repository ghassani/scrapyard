<?php 
/*
 * 
 * 
 */
namespace Spliced\Bundle\CommerceAdminBundle\Model;

/**
 * ListFilter
 */
class ListFilter extends Object
{
    
    public function serialize()
    {
        $data = $this->data;
        foreach($data as $key => $value){
            if(is_object($value) && method_exists($value, 'getId')){
                $data[$key] = $value->getId();
            }
        } 
        return serialize($data);
    }
}
