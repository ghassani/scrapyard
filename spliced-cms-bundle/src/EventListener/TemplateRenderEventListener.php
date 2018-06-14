<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\EventListener;

use Spliced\Bundle\CmsBundle\Manager\TemplateManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Spliced\Bundle\CmsBundle\Event\TemplateRenderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spliced\Bundle\CmsBundle\Event\Event;

/**
 * TemplateRenderEventListener
 */
class TemplateRenderEventListener
{

    protected $templateManager;

    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param TemplateManager $templateManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(TemplateManager $templateManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->templateManager = $templateManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * onRender
     *
     * When a page is getting rendered
     *
     * @param TemplateRenderEvent $event
     * @return $this
     */
    public function onRender(TemplateRenderEvent $event)
    {
        $request            = $event->getRequest();
        $site               = $event->getSite();
        $template           = $event->getTemplate();
        $extendedTemplate   = $event->getExtendedTemplate();
        $viewParameters     = $event->getViewParameters();
        
        // call all extension pre-render hooks, if a response is returned, return that response
        // and end the event call, start with the extended template, if defined
        if ($extendedTemplate && $extendedTemplate->getExtensions()) {
            $result = $this->processExtensionPreRender($event, $extendedTemplate->getExtensions());
            if ($result instanceof Response) {
                return $event->setResponse($result);
            }
        }
        
        if ($template->getExtensions()) {
            $result = $this->processExtensionPreRender($event, $template->getExtensions());
            if ($result instanceof Response) {
                return $event->setResponse($result);
            }
        }
        
        // notify the event dispatcher of a pre-render event for additional hooks
        $this->eventDispatcher->dispatch(
            Event::TEMPLATE_PRE_RENDER,
            $event
        );
        
        // prepare initial view array and then call each extension to do its thing
        if ($extendedTemplate && $extendedTemplate->getExtensions()) {
            $this->processExtensionBuildView($event, $extendedTemplate->getExtensions(), $viewParameters);
        }
        
        if ($template->getExtensions()) {
            $this->processExtensionBuildView($event, $template->getExtensions(), $viewParameters);
        }
        
        // render the response
        $content = $this->templateManager->render($template, $site, $viewParameters);
        $response = new Response($content, 200);
        
        // post render event
        if ($extendedTemplate && $extendedTemplate->getExtensions()) {
            $this->processExtensionPostRender($event, $extendedTemplate->getExtensions(), $response, $viewParameters);
        }
        
        if ($template->getExtensions()) {
            $this->processExtensionPostRender($event, $template->getExtensions(), $response, $viewParameters);
        }
        // notify the event dispatcher of a post-render event for additional hooks
        $this->eventDispatcher->dispatch(
            Event::TEMPLATE_POST_RENDER,
            $event
        );
        // all done
        return $event->setResponse($response);
    }

    /**
     * processExtensionPreRender
     *
     * Handles all extensions pre rendering functions
     *
     * @param TemplateRenderEvent $event
     * @param $extensions
     * @return bool
     */
    private function processExtensionPreRender(TemplateRenderEvent $event, $extensions)
    {
        foreach ($extensions as $_extension) {
            $extension = $this->templateManager->getExtension($_extension->getExtensionKey());
            if (false !== $extension) {
                $result = $extension->preRender($_extension, $event->getRequest());
                if ($result instanceof Response) {
                    return $result;
                }
            }
        }
        return true;
    }

    /**
     * processExtensionPostRender
     *
     * Handles all extension post render functions
     *
     * @param TemplateRenderEvent $event
     * @param $extensions
     * @param Response $response
     * @param array $viewParameters
     * @return $this
     */
    private function processExtensionPostRender(TemplateRenderEvent $event, $extensions, Response $response, array $viewParameters = array())
    {
        foreach ($extensions as $_extension) {
            $extension = $this->templateManager->getExtension($_extension->getExtensionKey());
            if (false !== $extension) {
                $result = $extension->postRender($_extension, $event->getRequest(), $response, $viewParameters);
                if ($result instanceof Response) {
                    return $event->setResponse($result);
                }
            }
        }
    }
    
    /**
     * processExtensionBuildView
     *
     * Handles all extensions view parameter building
     *
     * @param TemplateRenderEvent $event
     * @param $extensions
     * @param array $viewParameters
     */
    private function processExtensionBuildView(TemplateRenderEvent $event, $extensions, array &$viewParameters = array())
    {
        foreach ($extensions as $_extension) {
            $extension = $this->templateManager->getExtension($_extension->getExtensionKey());
            if (false !== $extension) {
                $viewParameters = $extension->prepareView($_extension, $event->getRequest(), $viewParameters);
            }
        }
    }
}