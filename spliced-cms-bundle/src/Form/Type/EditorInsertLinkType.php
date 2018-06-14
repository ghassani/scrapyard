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

use Spliced\Bundle\CmsBundle\Manager\SiteManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class EditorInsertLinkType extends AbstractType
{
    
    public function __construct(SiteManager $siteManager, EntityManager $em)
    {
        $this->siteManager = $siteManager;
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('page', 'choice', array(
            'label' => 'editor_insert_link.page_label',
            'translation_domain' => 'SplicedCmsBundle',
            'empty_value' => 'Select a Page',
            'required' => false,
            'choices' => $this->loadPageChoices(),
        ))->add('url', 'url', array(
                'label' => 'editor_insert_link.url_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))->add('anchor', 'text', array(
                'label' => 'editor_insert_link.anchor_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))->add('title', 'text', array(
                'label' => 'editor_insert_link.title_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))->add('class', 'text', array(
                'label' => 'editor_insert_link.class_label',
                'translation_domain' => 'SplicedCmsBundle',
            ));
    }

    protected function loadPageChoices()
    {
        $pages = $this->em->getRepository('SplicedCmsBundle:ContentPage')
        ->createQueryBuilder('cp')
        ->where('cp.site = :site')
        ->setParameter('site', $this->siteManager->getCurrentAdminSite()->getId())
        ->getQuery()->getResult();
        $return = array();
        foreach ($pages as $page) {
            if ($page->getRoutes()) {
                $return[$page->getRoutes()->first()->getName()] = (string) $page;
                continue;
            }
            $return[$page->getSlug()] = (string) $page;
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'editor_insert_link';
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