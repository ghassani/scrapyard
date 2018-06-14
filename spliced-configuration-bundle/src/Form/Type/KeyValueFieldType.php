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
use Spliced\Bundle\ConfigurationBundle\Form\DataTransformer\KeyValueTransformer;

class KeyValueFieldType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new KeyValueTransformer());
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $e) {
            $input = $e->getData();
            if (null === $input) {
                return;
            }

            if (!is_array($input)) {
                $input = unserialize($input);
            }

            foreach ($input as $key => $value) {
                $output[] = array(
                    'key' => $key,
                    'value' => $value
                );
            }
            $e->setData($output);
        }, 1);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'key_value';
    }

    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type' => new KeyValueFormType(),
            'allow_add' => true,
            'allow_delete' => true,
        ));
    }
}