<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout;

use Doctrine\ORM\EntityManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * CheckoutCustomFieldManager
 * 
 * Handles all custom fields, to assist in redering 
 * each ones form during the checkout process.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutCustomFieldManager
{
    protected $configurationManager;
    
    protected $fields = null;
    
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getDocumentManager
     *
     * @return ObjectManager
     */
    protected function getDocumentManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
    
    /**
     * getFields
     * 
     * @return array
     */
    public function getFields()
    {
        if(!$this->fields){
            $this->fields = $this->getDocumentManager()->getRepository(
                $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_CHECKOUT_CUSTOM_FIELD)
            )->getAllActive();
        }
        
        return $this->fields;
    }
    
    /**
     * getFieldsByStep
     * 
     * @param int $step - The Checkout Step 
     */
    public function getFieldsByStep($step)
    {
        $return = array();
        foreach($this->getFields() as $field) {
            if($field->getFieldStep() == $step) {
                $return[] = $field;
            }
        }
        return $return;
    }

    /**
     * getFieldsByName
     *
     * @param string $name - The Field Name
     */
    public function getFieldByName($name)
    {
        foreach($this->getFields() as $field) {
            if($field->getFieldName() == $name) {
                return $field;
            }
        }
        return false;
    }
}