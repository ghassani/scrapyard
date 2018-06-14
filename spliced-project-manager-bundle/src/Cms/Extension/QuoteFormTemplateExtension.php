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

use Spliced\Bundle\CmsBundle\Manager\SiteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\QuoteExtensionFormType;
use Spliced\Bundle\ProjectManagerBundle\Entity\Quote;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionImplementerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QuoteFormTemplateExtension implements TemplateExtensionImplementerInterface
{
    const EXTENSION_KEY             = 'spliced_pms_quote_form';
    const EXTENSION_NAME            = 'Project Manager Quote Form';
    const EXTENSION_DESCRIPTION     = 'Gives the template access to quote form variable to be able to render a quote form.';
    const EXTENSION_VERSION         = '1.0';
    protected $forms;
    protected $formFactory;
    protected $siteManager;
    protected $session;
    protected $em;

    /**
     * @param SiteManager $siteManager
     * @param FormFactory $formFactory
     * @param Session $session
     */
    public function __construct(SiteManager $siteManager, EntityManager $em, FormFactory $formFactory, Session $session, RouterInterface $router)
    {
        $this->siteManager = $siteManager;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->em = $em;
        $this->router = $router;
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
        $settings['formName']       = isset($settings['formName']) ? $settings['formName'] : 'quote_form';
        $settings['variableName']   = isset($settings['variableName']) ? $settings['variableName'] : 'quoteForm';
        $settings['successMessage'] = isset($settings['successMessage']) ? $settings['successMessage'] : 'Thank You For Your Submission';
        $settings['errorMessage']   = isset($settings['errorMessage']) ? $settings['errorMessage']  : 'Whoops.. looks like something was incorrect';
        $settings['successPage']    = isset($settings['successPage']) ? $settings['successPage']    : false;
        $return = array_merge($viewVariables, array(
            $settings['variableName'] => $this->forms[$settings['formName']]->createView()
        ));
        return $return;
    }
    /**
     * {@inheritDoc}
     */
    public function preRender(TemplateExtensionInterface $templateExtension, Request $request)
    {
        $settings = $templateExtension->getSettings();
        $settings['formName']       = isset($settings['formName']) ? $settings['formName'] : 'quote_form';
        $settings['variableName']   = isset($settings['variableName']) ? $settings['variableName'] : 'quoteForm';
        $settings['successMessage'] = isset($settings['successMessage']) ? $settings['successMessage'] : 'Thank You For Your Submission';
        $settings['errorMessage']   = isset($settings['errorMessage']) ? $settings['errorMessage']  : 'Whoops.. looks like something was incorrect';
        $settings['successPage']    = isset($settings['successPage']) ? $settings['successPage']    : false;
        $this->forms[$settings['formName']] = $this->formFactory->create(new QuoteExtensionFormType($settings), new Quote(), array());
        if ($request->getMethod() == 'POST') {
            if ($this->forms[$settings['formName']]->submit($request) && $this->forms[$settings['formName']]->isValid()) {
                $this->session->getFlashBag()->add('success', $settings['successMessage']);
                return new RedirectResponse($this->router->generate($settings['successPage']));
            } else {
                $this->session->getFlashBag()->add('error', $settings['errorMessage']);
            }
        }
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
        $contentPages = $this->em->getRepository('SplicedCmsBundle:ContentPage')
            ->createQueryBuilder('c')
            ->select('c, r')
            ->leftJoin('c.routes', 'r')
            ->where('c.site = :site')
            ->setParameter('site', $this->siteManager->getCurrentAdminSite()->getId())
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $pageChoices = array();
        foreach ($contentPages as $contentPage) {
            $pageChoices[$contentPage['pageKey']] = $contentPage['name'].' '.$contentPage['slug'];
        }
        $builder->add('variableName', 'text', array(
            'required' => true,
            'label' => 'Form Variable',
        ))->add('formName', 'text', array(
            'required' => true,
            'label' => 'Form Name',
        ))->add('errorMessage', 'text', array(
            'required' => false,
            'label' => 'Error Message',
        ))->add('successMessage', 'text', array(
            'required' => false,
            'label' => 'Success Message',
        ))->add('emailTo', 'email', array(
            'required' => true,
            'label' => 'Email To',
        ))->add('emailCc', 'email', array(
            'required' => true,
            'label' => 'Email CC',
        ))->add('emailSubject', 'text', array(
            'required' => true,
            'label' => 'Email Subject',
        ))->add('emailSubmitter', 'checkbox', array(
            'required' => true,
            'label' => 'Send Email to Submitter',
            'value' => 1,
        ))->add('services', 'textarea', array(
            'required' => true,
            'label' => 'Services to collect',
        ))->add('successPage', 'choice', array(
            'required' => true,
            'label' => 'Success Page',
            'choices' => $pageChoices,
        ));
    }
    /**
     * {@inheritDoc}
     */
    public function getSettingsFormTemplate()
    {
        return 'SplicedProjectManagerBundle:CmsExtension:quote_form_settings_form.html.twig';
    }
    /**
     * {@inheritDoc}
     */
    public function getSettingsViewTemplate()
    {
        return 'SplicedProjectManagerBundle:CmsExtension:quote_form_settings_view.html.twig';
    }
}