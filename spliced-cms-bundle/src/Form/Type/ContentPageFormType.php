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
use Spliced\Bundle\CmsBundle\Entity\ContentPage;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Spliced\Bundle\CmsBundle\Manager\SiteManager;

class ContentPageFormType extends AbstractType
{
	
	private $contentPage;
	
	/**
	 * 
	 */
	public function __construct(SiteManager $siteManager, ContentPage $contentPage = null)
	{
        $this->siteManager = $siteManager;
        $this->contentPage = $contentPage;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $currentSite = $this->siteManager->getCurrentAdminSite();
		$builder
        ->add('pageKey', 'text', array(
            'label' => 'content_page_form.page_key_label',
            'translation_domain' => 'SplicedCmsBundle',
            'constraints' => array(
               new Assert\Regex(array(
                   'pattern' => '/[^A-Za-z0-9_\-]/',
                   'match' => false,
                   'message' => 'Key can only contain alpha numeric characters (A-Z, 0-9), hyphens (-), and underscores(_)',
               ))
           )
        ))
		->add('name', 'text', array(
            'label' => 'content_page_form.name_label',
            'translation_domain' => 'SplicedCmsBundle',
        ))
        ->add('slug', 'text', array(
            'label' => 'content_page_form.slug_label',
            'translation_domain' => 'SplicedCmsBundle',
        ))
        ->add('template', new TemplateFormType($this->contentPage->getTemplate()), array(
            'label' => 'content_page_form.template_label',
            'translation_domain' => 'SplicedCmsBundle',
        ))
        ->add('layout', 'entity', array(
            'label' => 'content_page_form.layout_label',
            'translation_domain' => 'SplicedCmsBundle',
            'class' => 'SplicedCmsBundle:Layout',
            'empty_value' => 'No Layout',
            'required' => false,
            'query_builder' => function(EntityRepository $repository) use($currentSite) {
                $qb = $repository
                    ->createQueryBuilder('l')
                    ->orderBy('l.id', 'ASC');
                if ($currentSite) {
                     $qb->where('l.site = :site')
                         ->setParameter('site', $currentSite->getId());
                }
                return $qb;
            },
        ))->add('meta', 'collection', array(
            'type'         => new ContentPageMetaFormType(),
            'allow_add'    => true,
            'label' => 'content_page_form.meta_label',
            'translation_domain' => 'SplicedCmsBundle',
        ))->add('isActive', 'checkbox', array(
            'label' => 'content_page_form.is_active_label',
            'translation_domain' => 'SplicedCmsBundle',
            'value' => 1,
            'required' => false,
        ));
        
        if (!$currentSite) {
            $builder->add('site', 'entity', array(
                'label' => 'content_page_form.site_label',
                'translation_domain' => 'SplicedCmsBundle',
                'class' => 'SplicedCmsBundle:Site',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('s')
                        ->orderBy('s.id', 'ASC');
                },
            ));
        }
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'content_page';
	}
	
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\CmsBundle\Entity\ContentPage',
		));
	}
}