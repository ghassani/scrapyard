<?php
/*
 * This file is part of the SplicedCommerceBundle package. (c) Spliced Media <http://www.splicedmedia.com/> For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spliced\Component\Commerce\Product;

use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionValueInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Model\CartItemInterface;
use Symfony\Component\Form\FormFactory;
use Spliced\Component\Commerce\Cart\CartManager;
use Symfony\Component\Validator\Constraints;
use Doctrine\Common\Collections\Collection;
use Spliced\Component\Commerce\Helper\Product\Price as PriceHelper;

/**
 * ProductAttributeOptionUserDataFormBuilder
 * 
 * Handles the creation of products which in the shopping cart which
 * have attributes that either collects user input or collects a users
 * a users selection
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionUserDataFormBuilder {
    
    /**
     * Constructor
     *
     * @param FormFactory $formFactory            
     * @param CartManager $cartManager            
     */
    public function __construct(FormFactory $formFactory, CartManager $cartManager) {
        $this->formFactory = $formFactory;
        $this->cartManager = $cartManager;
    }
    
    /**
     * getFormFactory
     *
     * @return FormFactory
     */
    protected function getFormFactory() {
        return $this->formFactory;
    }
    
    /**
     * getCartManager
     *
     * @return CartManager
     */
    protected function getCartManager() {
        return $this->cartManager;
    }
    /**
     * buildForms
     *
     * @param array|Collection $produduct            
     */
    public function buildForms(array $formOptions = array()) {
        $forms = array();
        
        $buildItemForm = function ($items, &$forms) use(&$buildItemForm, $formOptions) {
            foreach($items as $item) {
                $forms[$item->getId()] = $this->buildForm($item, $formOptions);
                if ($item->hasChildren()) {
                    $buildItemForm($item->getChildren(), $forms);
                }
            }
        };
        
        $buildItemForm($this->getCartManager()->getItems(), $forms);
        
        return $forms;
    }
    
    /**
     * buildForm
     *
     * @param ProductInterface $produduct            
     */
    public function buildForm(CartItemInterface $item, array $formOptions = array()) {
        
        $builder = $this->getFormFactory()->createNamedBuilder(
              sprintf('item_%s_product_%s_user_data', $item->getId(), $item->getProduct()->getId()), 
              'form', 
              null,  
              $formOptions
        );
        
        $cartItemData = $item->getItemData();
        
        $hasFormFields = false;
                
        foreach($item->getProduct()->getAttributes() as $attribute) {        
            if(!in_array($attribute->getOption()->getOptionType(), array(
                ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION,
                ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT
            ))){
                continue;
            }
            
            $options = $attribute->getOption()->getOptionData();
            $constraints = array();
                
            $minLength = isset($options['min_length']) ? $options['min_length'] : 0;
            $maxLength = isset($options['max_length']) ? $options['max_length'] : 0;
            $labelClass = isset($options['label_class']) ? $options['label_class'] : null;
            $inputClass = isset($options['input_class']) ? $options['input_class'] : null;
            $required = isset($options['required']) && $options['required'] ? true : false;
            $validationType = isset($options['validation']) && $options['validation'] ? $options['validation'] : 1;
            $validationErrorMessage = isset($options['validation_error_message']) && $options['validation_error_message'] ? $options['validation_error_message'] : null;
            $formFieldType = 'text';
            
            if ($required) {
                $constraints[] = new Constraints\NotBlank();
            }
                
            if ($minLength || $maxLength) {
                $constraintOptions = array();
                
                if ($minLength) {
                    $constraintOptions['min'] = $minLength;
                    $constraintOptions['max'] = $maxLength;
                }
                if ($maxLength) {
                    $constraintOptions['max'] = $maxLength;
                }
                    
                $constraints[] = new Constraints\Length($constraintOptions);
            }
            if(isset($options['validators']) && count($options['validators'])) {
                foreach($options['validators'] as $validator) {
                    $validationErrorMessage = $validator['error_message'];
                    switch ($validator['type']) {
                        default :
                        case 1 : // No Validation
                            break;
                        case 2 : // Alpha Only
                            $constraints[] = new Constraints\Regexp(array (
                                'pattern' => '/^[A-Z\s]+$/i',
                                'message' => $validationErrorMessage ? $validationErrorMessage : 'Alphabetical Characters Only' 
                            ));
                            break;
                        case 3 : // Numeric Only
                            $constraints[] = new Constraints\Regexp(array (
                                'pattern' => '/^[0-9]+$/',
                                'message' => $validationErrorMessage ? $validationErrorMessage : 'Numbers Only' 
                            ));
                            break;
                        case 4 : // Alpha-Numeric Only
                            $constraints[] = new Constraints\Regexp(array (
                                'pattern' => '/^[A-Z0-9\s]+$/i',
                                'message' => $validationErrorMessage ? $validationErrorMessage : 'Alpha-Numeric Characters Only' 
                            ));
                            break;
                        case 5 : // Email
                            $constraints[] = new Constraints\Email(array (
                                'message' => $validationErrorMessage ? $validationErrorMessage : 'Invalid E-Mail' 
                            ));
                            break;
                        case 6 : // URL
                            break;
                        case 7 : // Luhn
                            $constraints[] = new Constraints\Luhn(array (
                                'message' => $validationErrorMessage ? $validationErrorMessage : 'Invalid' 
                            ));
                            break;
                        case 8 : // Regexp
                            if (isset($validator['regular_expression']) && $validator['regular_expression']) {
                                $constraints[] = new Constraints\Regexp(array (
                                    'pattern' => $validator['regular_expression'],
                                    'message' => $validationErrorMessage ? $validationErrorMessage : 'Invalid' 
                                ));
                            }
                            break;
                    }
                }
            }
            /*if ($item->isChild() && $item->isNonRemovable()) {
                $quantityInCart = $this->getCartManager()->getQuantity($item->getParent()->getProduct());
            } else {
                $quantityInCart = $this->getCartManager()->getQuantity($item->getProduct());
            }*/
            $quantityInCart = $item->getQuantity();
                
            $formFieldOptions = array (
                'constraints' => $constraints,
                'required' => $required,
                'label' => $attribute->getOption()->getPublicLabel(),
                'attr' => array (
                    'class' => $inputClass 
                ),
                'label_attr' => array (
                    'class' => $labelClass 
                ) 
            );
            
            
            
            if($attribute->getOption()->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION){
                $choices = array();
                $formFieldType = 'choice'; 
                $valueData = array();

                foreach($attribute->getOption()->getValues() as $value) {
                    $appendLabel = '';
                    if ($value->getPriceAdjustmentType()) {
                        switch ($value->getPriceAdjustmentType()) {
                            case PriceHelper::ADJUSTMENT_ADD_FIXED_PER_ITEM :
                                $appendLabel = sprintf(' (+$%s)', number_format($value->getPriceAdjustment(), 2));
                                break;
                            case PriceHelper::ADJUSTMENT_SUBTRACT_FIXED_PER_ITEM :
                                $appendLabel = sprintf(' (-$%s)', number_format($value->getPriceAdjustment(), 2));
                                break;
                            case PriceHelper::ADJUSTMENT_SUBTRACT_PERCENTAGE_PER_ITEM :
                                $appendLabel = sprintf(' (-%s%s)',(int) $value->getPriceAdjustment(), '%');
                                break;
                            case PriceHelper::ADJUSTMENT_ADD_PERCENTAGE_PER_ITEM :
                                $appendLabel = sprintf(' (-%s%s)',(int) $value->getPriceAdjustment(), '%');
                                break;
                            case PriceHelper::ADJUSTMENT_FIXED_PER_ITEM :
                                $appendLabel = sprintf(' (Sale Price $%s)', number_format($value->getPriceAdjustment(), 2));
                                break;
                        }
                    }
                    $_valueData = $value->getValueData();
                    if(isset($_valueData['products_to_add']) && count($_valueData['products_to_add'])){
                        $appendLabel .= sprintf(' - Adds %s Additional Products', count($_valueData['products_to_add']));
                    }
                    $valueData[$value->getId()] = $_valueData;
                    $choices[$value->getId()] = $value->getPublicValue() . $appendLabel;
                }
                
                $formFieldOptions = array_merge_recursive($formFieldOptions, array(
                    'choices' => $choices,
                    'empty_value' => '- Select '.$formFieldOptions['label'].' -',
                    'attr' => array(
                        'data-values' => json_encode($valueData),
                        
                    ),
                ));
            }
                        
            if (isset($options['collection_type']) && $options['collection_type'] == 1 && $quantityInCart > 1) {
                $hasFormFields = true;
                for($current = 1; $current <= $quantityInCart; $current ++) {
                    $builder->add($attribute->getOption()->getKey() . ($current !== 1 ? '_'.$current : ''), $formFieldType, array_merge($formFieldOptions, array(
                        'label' => $attribute->getOption()->getPublicLabel() . ' ' . $current,
                    ))); 
                }
            } else {
                $hasFormFields = true;
                $builder->add($attribute->getOption()->getKey(), $formFieldType, $formFieldOptions);
            }
        }
        
        
        if($hasFormFields === true){
            //remove any extra fields before binding, if we have any data to bind to prevent
            // form errors
            if (isset($cartItemData['user_data']) && is_array($cartItemData['user_data'])) {
                foreach($cartItemData['user_data'] as $fieldToCheck => $value){
                    if(!$builder->has($fieldToCheck)){
                        unset($cartItemData['user_data'][$fieldToCheck]);
                    }
                }
                $builder->setData($cartItemData['user_data']);
            }
            return $builder->getForm();
        }
        return null;
    }
}
