<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ProductType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                            ->add('categoryId')
            
                            ->add('productId')
            
                            ->add('pageId')
            
                            ->add('otherId')
            
                            ->add('requestPath')
            
                            ->add('targetPath')
            
                            ->add('options')
            
                            ->add('description')
            
                            ->add('category')
            
                            ->add('product')
            
                            ->add('page')
                    
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_route';
    }
    
}
