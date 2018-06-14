<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectInvoiceFormType extends AbstractType
{
	
	/**
	 *
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
            ->add('startDate', 'text', array(
                'required' => true,
                'attr' => array('class' => 'datepicker'),
                'label' => 'project_invoice_form.start_date_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('completionDate', 'text', array(
                'required' => false,
                'attr' => array('class' => 'datepicker'),
                'label' => 'project_invoice_form.completion_date_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('lineItems', 'collection', array(
                'type' => new ProjectInvoiceLineItemFormType(),
                'allow_add' => true,
                'by_reference' => false,
                'label' => 'project_invoice_form.line_items_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ));
	}
	
	/**
	 *
	 */
	public function getName(){
		return 'project_invoice';
	}
	
	/**
	 * 
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectInvoice',
		));
	}
}