<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration\Type;

use Spliced\Component\Commerce\Model\ConfigurationInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * TypeInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface TypeInterface
{
    /**
     * getName
     * 
     * A unique string to identify the configuration field type
     * 
     * @return string
     */
    public function getName();
    
    /**
     * getApplicationValue
     *
     * Process a configuration field and return a value for the application
     *
     * @param mixed $value
     * 
     * @return mixed
     */
    public function getApplicationValue($value);
    
    /**
     * getDatabaseValue
     *
     * Process a configuration field and return a value for the database
     *
     * @param mixed $value
     * 
     * @return mixed
     */
    public function getDatabaseValue($value);
    
    /**
     * buildForm
     *
     * Process a configuration field and build upon a FormBuilderInterface
     * 
     * You should also add any validation rules to your form field(s) in this method
     *
     * @param ConfigurationInterface $configData
     * @param FormBuilderInterface $form
     */
    public function buildForm(ConfigurationInterface $configData, FormBuilderInterface $form);
}
