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

use Spliced\Bundle\CmsBundle\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFormType extends AbstractType
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
        $builder->add('name', 'text', array(
            'label' => 'menu_form.name_label',
            'translation_domain' => 'SplicedCmsBundle',
          ))
          ->add('menuKey', 'text', array(
                'label' => 'menu_form.menu_key_label',
                'translation_domain' => 'SplicedCmsBundle',
          ))
          ->add('items', 'collection', array(
              'type'         => new MenuItemFormType($this->menu),
              'allow_add'    => true,
              'label' => 'menu_form.items_label',
              'translation_domain' => 'SplicedCmsBundle',
          ))->add('menuTemplate');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'menu';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\Menu',
        ));
    }
}