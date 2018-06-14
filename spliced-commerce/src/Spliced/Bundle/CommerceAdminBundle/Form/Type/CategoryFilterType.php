<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CustomerType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryFilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CommerceAdminBundle\Model\ListFilter'
        ));
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_category_filter_type';
    }
}
