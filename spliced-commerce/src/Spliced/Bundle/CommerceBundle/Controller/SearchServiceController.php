<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Spliced\Component\Commerce\Controller\ServiceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Bundle\CommerceBundle\Form\Type\ProductFilterFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactory;

/**
 * SearchServiceController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SearchServiceController extends ServiceController
{
    /**
     * 
     */
    public function __construct(AbstractType $attributeFilterFormType, FormFactory $formFactory)
    {
        $this->attributeFilterFormType = $attributeFilterFormType;
        $this->formFactory = $formFactory;
    }
    
    /**
     * getFormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }
    
    /**
     * getAttributeFilterFormType
     */
    public function getAttributeFilterFormType()
    {
        return $this->attributeFilterFormType;
    }
    
    /**
     * 
     */
    public function attributeFilterFormAction()
    {
        
        return array(
            'form' => $this->getFormFactory()->create($this->getAttributeFilterFormType())->createView(),
        );
    }
}