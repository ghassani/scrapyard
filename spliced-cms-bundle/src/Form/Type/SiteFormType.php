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

use Spliced\Bundle\CmsBundle\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SiteFormType extends AbstractType
{
    
    public function __construct(Site $site)
    {
        $this->site = $site;
    }
    
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('domain', ($this->site->getId() ? 'hidden' : 'text'), array(
            'label' => 'site_form.domain_label',
            'translation_domain' => 'SplicedCmsBundle',
            'required' => true
        ))->add('isActive', 'checkbox', array(
            'required' => false,
            'value' => true,
            'label' => 'site_form.is_active_label',
            'translation_domain' => 'SplicedCmsBundle',
        ));
        if ($this->site->getId()) {
            $builder->add('defaultPage', 'entity', array(
                'label' => 'site_form.default_page_label',
                'translation_domain' => 'SplicedCmsBundle',
                'class' => 'SplicedCmsBundle:ContentPage',
                'required' => true,
                'empty_value' => 'Select Default Page',
                'query_builder' => function(EntityRepository $repository) {
                        return $repository
                           ->createQueryBuilder('c')
                           ->where('c.site = :site')
                           ->setParameter('site', $this->site->getId());
                    },
            ));
        }
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'site';
	}
	
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\CmsBundle\Entity\Site',
		));
	}
}