<?php
/*
* This file is part of the SplicedProjectManager package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ProjectManagerBundle\Cms\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionImplementerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectsTemplateExtension implements TemplateExtensionImplementerInterface
{
    const EXTENSION_KEY = 'spliced_pms_projects';
    const EXTENSION_NAME = 'Projects Loader';
    const EXTENSION_DESCRIPTION = 'Load a collection of projects for display in a template';
    const EXTENSION_VERSION         = '1.0';
    protected $forms;
    protected $session;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::EXTENSION_KEY;
    }
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return static::EXTENSION_NAME;
    }
    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return static::EXTENSION_DESCRIPTION;
    }
    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return static::EXTENSION_VERSION;
    }
    /**
     * {@inheritDoc}
     */
    public function prepareView(TemplateExtensionInterface $templateExtension, Request $request, array $viewVariables)
    {
        $settings = $templateExtension->getSettings();
        $settings['variableName'] = isset($settings['variableName']) ? $settings['variableName'] :'projects';
        $projectIds = explode(',', str_replace(' ', '', $settings['projectIds']));
        $viewVariables[$settings['variableName']] = $this->em->getRepository('SplicedProjectManagerBundle:Project')
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.id IN(:ids)')
            ->setParameter('ids', $projectIds)
            ->getQuery()
            ->getResult();
        return $viewVariables;
    }
    /**
     * {@inheritDoc}
     */
    public function preRender(TemplateExtensionInterface $templateExtension, Request $request)
    {
        $settings = $templateExtension->getSettings();
    }
    /**
     * {@inheritDoc}
     */
    public function postRender(TemplateExtensionInterface $templateExtension, Request $request, Response $response, array $viewVariables)
    {
    }
    /**
     * {@inheritDoc}
     */
    public function buildSettingsForm(FormBuilderInterface $builder, array $options)
    {
        $projects = $this->em->getRepository('SplicedProjectManagerBundle:Project')
        ->createQueryBuilder('p')
        ->select('p.id, p.name')
        ->getQuery()
        ->getResult(Query::HYDRATE_ARRAY);
        $projectChoices = array();
        foreach ($projects as $project) {
            $projectChoices[$project['id']] = $project['name'];
        }
        $builder->add('variableName', 'text', array(
            'required' => true,
            'label' => 'Variable Name',
        ))->add('projects', 'choice', array(
            'required' => true,
            'multiple' => true,
            'label' => 'Projects',
            'choices' => $projectChoices
        ));
    }
    /**
     * {@inheritDoc}
     */
    public function getSettingsFormTemplate()
    {
        return 'SplicedProjectManagerBundle:CmsExtension:projects_settings_form.html.twig';
    }
    /**
     * {@inheritDoc}
     */
    public function getSettingsViewTemplate()
    {
        return 'SplicedProjectManagerBundle:CmsExtension:projects_settings_view.html.twig';
    }
}