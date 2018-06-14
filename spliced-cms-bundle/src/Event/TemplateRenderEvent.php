<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Event;

use Spliced\Bundle\CmsBundle\Manager\TemplateManager;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Spliced\Bundle\CmsBundle\ContentBlockEvent;

/**
 * TemplateRenderEvent
 *
 */
class TemplateRenderEvent extends Event
{
    
    protected $request;
    
    protected $site;
    
    protected $template;
    
    protected $extendedTemplate;
    
    protected $initialViewParameters = array();
    
    protected $viewParameters = array();
    
    protected $response;

    public function __construct(Request $request, TemplateInterface $template, SiteInterface $site, TemplateInterface $extendedTemplate = null, array $initialViewParameters = array())
    {
        $this->site = $site;
        $this->request = $request;
        $this->template = $template;
        $this->extendedTemplate = $extendedTemplate;
        $this->viewParameters = $initialViewParameters;
        $this->initialViewParameters = $initialViewParameters;
    }

    /**
     * @param array $initialViewParameters
     */
    public function setInitialViewParameters($initialViewParameters)
    {
        $this->initialViewParameters = $initialViewParameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getInitialViewParameters()
    {
        return $this->initialViewParameters;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Spliced\Bundle\CmsBundle\Model\TemplateInterface $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return \Spliced\Bundle\CmsBundle\Model\TemplateInterface
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param array $viewParameters
     */
    public function setViewParameters($viewParameters)
    {
        $this->viewParameters = $viewParameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getViewParameters()
    {
        return $this->viewParameters;
    }

    /**
     * @param \Spliced\Bundle\CmsBundle\Model\TemplateInterface $extendedTemplate
     */
    public function setExtendedTemplate($extendedTemplate)
    {
        $this->extendedTemplate = $extendedTemplate;
        return $this;
    }

    /**
     * @return \Spliced\Bundle\CmsBundle\Model\TemplateInterface
     */
    public function getExtendedTemplate()
    {
        return $this->extendedTemplate;
    }

    /**
     * @param \Spliced\Bundle\CmsBundle\Model\SiteInterface $site
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }
    
    /**
     * @return \Spliced\Bundle\CmsBundle\Model\SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }
}