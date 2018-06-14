<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Bundle\ProjectManagerBundle\Entity\ProjectMedia;

class ProjectInvoiceLineItemFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
  		$builder
            ->add('title','text', array(
                'required' => true,
                'label' => 'project_invoice_line_item_form.title_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('sku','text', array(
                'required' => false,
                'label' => 'project_invoice_line_item_form.sku_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('description','text', array(
                'required' => false,
                'label' => 'project_invoice_line_item_form.description_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('price','text', array(
                'required' => true,
                'label' => 'project_invoice_line_item_form.price_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('quantity','text', array(
                'required' => true,
                'label' => 'project_invoice_line_item_form.quantity_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
			;
	}

	
	/**
	 *
	 */
	public function getName(){
		return 'project_invoice_line_item';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectInvoiceLineItem',
			'cascade_validation' => true,
	        'allow_add' => true,
	        'allow_delete' => true,
	        'by_reference' => false,
		));
	}
}