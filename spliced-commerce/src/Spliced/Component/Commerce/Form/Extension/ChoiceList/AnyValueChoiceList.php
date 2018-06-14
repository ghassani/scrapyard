<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
/**
 * AnyValueChoiceList
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AnyValueChoiceList implements ChoiceListInterface
{
    protected $choices = array();
    protected $values = array();
    

    public function getChoices()
    {
        return $this->choices;
    }


    public function getValues()
    {
        return $this->values;
    }
    
    public function getPreferredViews()
    {
        return array();
    }
    
    public function getRemainingViews()
    {
        return array();
    }
    
    public function getChoicesForValues(array $values)
    {
        if(count($values)){
            $this->values = $values;
            $this->choices = $values;
        }
        return $values;
    }
    
    public function getValuesForChoices(array $choices)
    {
        if(count($choices)){
            $this->values = $choices;
            $this->choices = $choices;
        }
        return $choices;
    }
    
    
    public function getIndicesForChoices(array $choices)
    {
       
    }
    
    public function getIndicesForValues(array $values)
    {

    }


}