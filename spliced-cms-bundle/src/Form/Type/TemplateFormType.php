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
use Spliced\Bundle\CmsBundle\Entity\Template;

class TemplateFormType extends AbstractType
{

	private $template;
	
	/**
	 *
	 */
	public function __construct(Template $template = null)
	{
		$this->template = $template;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$template = $this->template;
		
		$builder->add('version', new TemplateVersionFormType($template ? $template->getVersion() : null), array(
			'label' => 'template_form.version_label',
			'translation_domain' => 'SplicedCmsBundle',
		))->add('label', 'text', array(
            'mapped' => false,
            'label' => 'template_form.label_label',
            'translation_domain' => 'SplicedCmsBundle',
            'required' => false,
        ));
		if ($template) {
           $currentLabel = $this->template->getVersion() ? $this->template->getVersion()->__toString().' (Current)' : 'Current';
			$builder->add('revert', 'entity', array(
			  	'label' => 'template_form.revert_label',
			  	'translation_domain' => 'SplicedCmsBundle',
			  	'mapped' => false,
				'class' => 'SplicedCmsBundle:TemplateVersion',
                'empty_value' => $currentLabel,
                'required' => false,
				'query_builder' => function(EntityRepository $repository) use($template) {
					return $repository
					  ->createQueryBuilder('tv')
				      ->where('tv.template = :template AND tv.id != :currentVersion')
                      ->setParameter('template', $this->template->getId())
                      ->setParameter('currentVersion', $this->template->getVersion()->getId())
					  ->orderBy('tv.createdAt', 'DESC');
				},
			  ));
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'template';
	}
	
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\CmsBundle\Entity\Template',
		));
	}
}