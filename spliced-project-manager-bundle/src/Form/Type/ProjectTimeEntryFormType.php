<?php

namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectTimeEntryFormType extends AbstractType
{

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('entryDate','date', array(
                'required' => true,
                'widget' => 'single_text',
                'label' => 'project_time_entry_form.entry_date_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))
            ->add('entryTime','number', array(
                'required' => true,
                'label' => 'project_time_entry_form.entry_time_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ))->add('entryNote','textarea', array(
                'required' => true,
                'label' => 'project_time_entry_form.entry_note_label',
                'translation_domain' => 'SplicedProjectManagerBundle',
            ));
    }

    /**
     *
     */
    public function getName(){
        return 'project_time_entry';
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\ProjectTimeEntry',
        ));
    }
}