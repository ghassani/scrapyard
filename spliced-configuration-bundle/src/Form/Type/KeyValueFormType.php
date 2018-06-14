<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class KeyValueFormType extends AbstractType

{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('key', 'text', array());
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $input = $event->getData();
            $form = $event->getForm();
            if (is_null($input)) {
                return;
            }
            if (is_array($input) && is_array($input['value'])) {
                $form->add('value', new KeyValueFieldType(), array());
            } else {
                $form->add('value', 'text', array());
            }
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'key_value';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}