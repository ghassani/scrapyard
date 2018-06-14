<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AdvancedSearchFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AdvancedSearchFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('query', 'text')
        ->add('type', 'choice', array('choices' => array('Loose','Tight','Exact')));
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'advanced_search';
    }
}