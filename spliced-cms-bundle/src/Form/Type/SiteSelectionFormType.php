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
use Doctrine\ORM\EntityRepository;

class SiteSelectionFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('site', 'entity', array(
            'label' => 'site_selection_form.site_label',
            'translation_domain' => 'SplicedCmsBundle',
            'class' => 'SplicedCmsBundle:Site',
            'required' => false,
            'query_builder' => function(EntityRepository $repository) {
                return $repository
                  ->createQueryBuilder('s')
                  ->orderBy('s.id', 'ASC');
            },
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'site_selection';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

}