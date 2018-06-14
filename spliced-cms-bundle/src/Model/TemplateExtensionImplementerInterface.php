<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilderInterface;

interface TemplateExtensionImplementerInterface
{
    /**
     * getKey
     *
     * The unique key to identify this extension
     *
     * @return string
     */
    public function getKey();

    /**
     * getName
     *
     * The user friendly name of the extension
     * @return string
     */
    public function getName();

    /**
     * getDescription
     *
     * The user friendly description of the extension
     *
     * @return string
     */
    public function getDescription();

    /**
     * getVersion
     *
     * The current version of the extension
     *
     * @return float
     */
    public function getVersion();

    /**
     * Called before a template gets rendered, for the user whenever
     * the event `spliced_cms.template.render` is dispatched
     *
     * @param TemplateExtensionInterface $templateExtension
     * @param Request $request
     * @return mixed
     */
    public function preRender(TemplateExtensionInterface $templateExtension, Request $request);

    /**
     * prepareView
     *
     * Return an array of variables to be included in the view. Called
     * whenever the event `spliced_cms.template.render` is dispatched
     * @param TemplateExtensionInterface $templateExtension
     * @param Request $request
     * @param array $viewVariables
     * @return mixed
     */
    public function prepareView(TemplateExtensionInterface $templateExtension, Request $request, array $viewVariables);

    /**
     * Called after a template gets rendered, but
     * before it is rendered for the user whenever
     * the event `spliced_cms.template.render` is dispatched
     *
     * @param TemplateExtensionInterface $templateExtension
     * @param Request $request
     * @param Response $response
     * @param array $viewVariables
     * @return mixed
     */
    public function postRender(TemplateExtensionInterface $templateExtension, Request $request, Response $response, array $viewVariables);

    /**
     * buildSettingsForm
     *
     * Build the extension form for its settings when
     * managing the extension from the administrative
     * area
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return mixed
     */
    public function buildSettingsForm(FormBuilderInterface $builder, array $options);

    /**
     * getSettingsFormTemplate
     *
     * Returns the string to the path of the template to use
     * when rendering the extension form settings for this extension
     * in the administrative area
     *
     * @return string
     */
    public function getSettingsFormTemplate();

    /**
     * getSettingsViewTemplate
     *
     * Returns the string to the path of the template to use
     * when rendering the extension settings view for this extension
     * in the administrative area
     *
     * @return string
     */
    public function getSettingsViewTemplate();
    
}