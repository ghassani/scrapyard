<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Spliced\Component\Commerce\Model;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CheckoutCustomFieldsFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutCustomFieldsFormType extends AbstractType
{

    /**
     * Constructor
     * 
     * @param Entity\CheckoutCustomField $field
     */
    public function __construct(Model\OrderInterface $order, array $fields)
    {
        $this->fields = $fields;
        $this->order = $order;
    }
    
    /**
     * getFields
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * getOrder
     *
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        foreach($this->getFields() as $field) {
          
            $currentFieldValue = $this->getOrder()->getCustomField($field->getFieldName());
            
            $builder->add(
                $field->getFieldName(), 
                $field->getFieldType(), 
                array_merge(
                    array(
                        'data' => $currentFieldValue ? $currentFieldValue->getFieldValue() : null,
                        'label' => $field->getFieldLabel(),
                        'required' => false,
                    ),
                    $field->getFieldParams()
                )
            )->add($field->getFieldName().'_params','hidden', array('data' => serialize($field->getValidationParams())));
        }
    }
     
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'cascade_validation' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'checkout_custom_fields';
    }

}
