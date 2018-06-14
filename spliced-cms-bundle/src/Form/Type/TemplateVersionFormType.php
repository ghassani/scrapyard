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
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;

class TemplateVersionFormType extends AbstractType
{
	/**
	 *
	 */
	public function __construct(TemplateVersion $templateVersion = null)
	{
		$this->templateVersion = $templateVersion;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('content', 'textarea');
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'template_version';
	}
	
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Spliced\Bundle\CmsBundle\Entity\TemplateVersion',
		));
	}
}