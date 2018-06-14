<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionImplementerInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Templating\EngineInterface;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use  Spliced\Bundle\CmsBundle\Event\Event;
use  Spliced\Bundle\CmsBundle\Event\TemplateEvent;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

/**
 * TemplateManager
 *
 * Handles rendering, saving/updating, and managing the lifecycle
 * of a managed template and it's associated versions.
 *
 * @package Spliced\Bundle\CmsBundle
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TemplateManager
{
    
    const TEMPLATE_PATH = '@spliced_cms_template';
    
    protected $configurationManager;
    
    protected $templatingEngine;
    
    protected $eventDispatcher;
    
    protected $extensions;
    
    protected $em;
	
    public function __construct(ConfigurationManagerInterface $configurationManager, SiteManager $siteManager, EngineInterface $templateEngine, FilesystemLoader $templateFileLoader, EntityManager $em, EventDispatcherInterface $eventDispatcher, array $extensions = array())
	{
        $this->em                   = $em;
        $this->extensions           = new ArrayCollection($extensions);
        $this->eventDispatcher      = $eventDispatcher;
        $this->templateEngine       = $templateEngine;
        $this->siteManager          = $siteManager;
        $this->configurationManager = $configurationManager;
        $this->templateFileLoader   = $templateFileLoader;
	}

    /**
     * findTemplateById
     *
     * Locate a template reference in the database
     *
     * @param $id
     * @return Template | null
     */
    public function findTemplateById($id)
	{
		return $this->getEntityManager()
		->getRepository('SplicedCmsBundle:Template')
		->findTemplateById($id);
	}

    /**
     * build
     *
     * Builds a template and saves it to the filesystem
     * in the self::$templateDir path
     *
     *
     * @param TemplateInterface $template
     * @param SiteInterface $site
     * @param TemplateInterface $extendedTemplate
     *
     * @throws UnexpectedValueException
     * @return int
     */
    public function build(TemplateInterface $template, SiteInterface $site, TemplateInterface $extendedTemplate = null)
	{
        if (!$template->getFilename()) {
            throw new \InvalidArgumentException('Cannot build a template without a unique filename');
        }

        $templateDir = $site->getTemplateDir();
        
        if ($extendedTemplate) {
            
            $content    = sprintf('{# %s %s %s #}%s', $template->getId(), $template->getFilename(), date('c'), PHP_EOL);
            $content   .= sprintf("{%% extends '%s' %%}", $this->buildTemplatePath($extendedTemplate, $site));
			$content   .= PHP_EOL.PHP_EOL;
			$content   .= $template->getActiveVersion()->getContent();
            $baseDir    = dirname($templateDir.'/'.$extendedTemplate->getFilename());
            
            if (!file_exists($baseDir)) {
                mkdir($baseDir, 0755, true);
            }
            
            if (!file_exists($templateDir.'/'.$extendedTemplate->getFilename())) {
                $this->build($extendedTemplate, $site, null);
            }
			
		} else {
			$content = $template->getActiveVersion()->getContent();
		}

        $baseDir = dirname($templateDir.'/'.$template->getFilename());

        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        $status = file_put_contents($templateDir.'/'.$template->getFilename(), $content);
        
        if (false == $status) {
            throw \UnexpectedValueException(sprintf('Could Not Save File %s In %s', $template->getFilename(), $templateDir));
        }
	}

    /**
     * save
     *
     * Saves or updates a template.
     * Flushes the EntityManager
     *
     * @param TemplateInterface $template
     * @param SiteInterface $site
     * @param TemplateInterface $extendedTemplate
     * @throws \LogicException - When template missing version
     */
    public function save(TemplateInterface $template, SiteInterface $site, TemplateInterface $extendedTemplate = null, $build = true)
    {
        if ($template->getId()) {
            return $this->update($template, $site, $extendedTemplate, $build);
        }

        if (!$template->getVersion()) {
            throw new \LogicException(sprintf('Entity Template is missing associated version'));
        }

        if (!$template->getActiveVersion()) {
            $template->setActiveVersion($template->getVersion());
        }

        $this->getEntityManager()->persist($template);
        $this->getEntityManager()->persist($template->getVersion());
        $this->getEntityManager()->flush();

        if (true === $build){
            $this->build($template, $site, $extendedTemplate);
        }
        
        // notify the event dispatcher that
        // it has been saved
        $this->getEventDispatcher()->dispatch(
            Event::TEMPLATE_SAVE,
            new TemplateEvent($template, $site)
        );
    }

    /**
     * delete
     *
     * @param TemplateInterface $template - Template to be deleted
     * @param SiteInterface $site - For file location reference, required
     */
    public function delete(TemplateInterface $template, SiteInterface $site, $flush = true)
    {
        $file = new \SplFileInfo($site->getTemplateDir().'/'.$template->getFilename());
        
        if ($file->isFile() && $file->getRealPath() !== false) {
            @unlink($file->getRealPath());
        }
        
        $this->getEntityManager()->remove($template);
        
        $this->getEventDispatcher()->dispatch(
            Event::TEMPLATE_DELETE,
            new TemplateEvent($template, $site)
        );

        if (true === $flush){
            $this->getEntityManager()->flush();
        }
    }

    /**
     * update
     *
     * Saves or updates a template.
     * Flushes the EntityManager
     *
     * @param TemplateInterface $template
     * @param SiteInterface $site
     * @param TemplateInterface $extendedTemplate
     * @throws \LogicException - When template missing version
     */
    public function update(TemplateInterface $template, SiteInterface $site, TemplateInterface $extendedTemplate = null, $build = true)
    {
        
        if (!$template->getId()) {
            return $this->save($template, $site, $extendedTemplate, $build);
        }
        
        if (!$template->getVersion()) {
            throw new \LogicException(sprintf('Entity Template is missing associated version'));
        }

        if (!$template->getActiveVersion()) {
            $template->setActiveVersion($template->getVersion());
        }
        
        $this->getEntityManager()->persist($template);
        $this->getEntityManager()->persist($template->getVersion());
        $this->getEntityManager()->flush();
        
        if (true === $build){
            $this->build($template, $site, $extendedTemplate);
        }
        
        // notify the event dispatcher that
        // it has been updated
        $this->getEventDispatcher()->dispatch(
            Event::TEMPLATE_UPDATE,
            new TemplateEvent($template)
        );
    }

    /**
     * render
     *
     * Renders a template and returns HTML to be displayed on the screen
     * or put into a buffer
     *
     * @param TemplateInterface $template
     * @param SiteInterface $site
     * @param array $parameters - Optional view parameters to use in the rendering process
     * @return mixed
     * @throws Exception when template not found, or template has fatal errors
     */
    public function render(TemplateInterface $template, SiteInterface $site, $parameters = array())
    {
        $this->getTemplateFileLoader()->prependPath($site->getTemplateDir(), $site->getDomain());
        return $this->getTemplateEngine()->render($this->buildTemplatePath($template, $site), $parameters);
    }

    /**
     * buildTemplatePath
     *
     * Returns the full path used by the template engine
     * to render the actual content of the template, or to
     * be used in template file management.
     *
     * @access public
     * @param Template $template
     * @return string
     */
    public function buildTemplatePath(TemplateInterface $template, SiteInterface $site)
    {
       return sprintf('@%s/%s',
           $site->getDomain(),
           $template->getFilename()
        );
    }

    /**
     * getExtensions
     *
     * @return ArrayCollection
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * getExtension
     *
     * @param $name
     * @return bool
     */
    public function getExtension($key)
    {
        foreach ($this->getExtensions() as $_key => $extension) {
            if ($_key == $key) {
                return $extension;
            }
        }
        return false;
    }

    /**
     * hasExtension
     *
     * @param $name
     * @return bool
     */
    public function hasExtension($key)
    {
        foreach ($this->getExtensions() as $_key => $extension) {
            if ($_key == $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * addExtension
     *
     * @param $name
     * @param TemplateExtensionImplementerInterface $extension
     * @return $this
     */
    public function addExtension(TemplateExtensionImplementerInterface $extension)
    {
        $this->extensions->set($extension->getKey(), $extension);
        return $this;
    }

    /**
     * getConfigurationManager
     *
     * @access private
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getEntityManager
     *
     * @access private
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * getTemplateEngine
     *
     * @access private
     * @return EngineInterface
     */
    protected function getTemplateEngine()
    {
        return $this->templateEngine;
    }

    /**
     * getEventDispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @return SiteManager
     */
    protected function getSiteManager()
    {
        return $this->siteManager;
    }

    /**
     * @return FilesystemLoader
     */
    private function getTemplateFileLoader()
    {
        return $this->templateFileLoader;
    }
    
}