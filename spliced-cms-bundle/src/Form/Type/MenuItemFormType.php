<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spliced\Bundle\CmsBundle\Entity\Menu;

class MenuItemFormType extends AbstractType
{
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', 'text', array('data' => 0))
            ->add('targetPath', 'text', array('required' => true))
            ->add('anchorText')
            ->add('titleText')
            ->add('targetType', 'choice', array(
                'mapped' => false,
                //'expanded' => true,
                'choices' => array(
                    'route_name' => 'Route By Name',
                    'route_path' => 'Route By Path',
                    'external'   => 'External URL'
                ),
            ))->add('options', new MenuItemOptionsFormType(), array(
            ))/*->add('children', 'collection', array(
                'type'         => new MenuItemFormType(),
                'allow_add'    => true,
                'label' => 'menu_form.children_items_label',
                'translation_domain' => 'SplicedCmsBundle',
            ));;*/
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'menu_item';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver))
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\MenuItem',
        ));
    }
}