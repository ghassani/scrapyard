<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CategoryFilterType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       /* $builder
            ->add('firstName', 'text')
            ->add('middleName',  'text')
            ->add('lastName',  'text')
            ->add('emailAddress', 'email')
            ->add('plainPassword', 'repeated', array('type' => 'password', 'first_name' => 'Password', 'second_name' => 'Repeat Password'));*/
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'csrf_protection' => true,
        ));
    }

    public function getName()
    {
        return 'category_filter';
    }
}
