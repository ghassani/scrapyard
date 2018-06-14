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

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Templating\EngineInterface;

class SiteHostManager
{
    
    protected $siteManager;
    
    protected $kernel;
    
    protected $templateEngine;

    /**
     * Constructor
     *
     * @param SiteManager $siteManager
     * @param KernelInterface $kernel
     */
    public function __construct(SiteManager $siteManager, EngineInterface $templateEngine, KernelInterface $kernel)
    {
        $this->siteManager = $siteManager;
        $this->templateEngine = $templateEngine;
        $this->kernel = $kernel;
    }

    /**
     * installFilesystem
     *
     * Install's
     *
     * @param SiteInterface $site
     */
    public function installFilesystem(SiteInterface $site)
    {
        if (!$site->getId()) {
            $this->getSiteManager()->save($site);
        }

        if ($site->getAliasOf()) {
            return $this->installFilesystem($site->getAliasOf());
        }

        $baseDir          = new \SplFileInfo($this->getKernel()->getRootDir().'/../');
        $skelDir          = $baseDir->getRealPath().'/skeleton/site';
        $skelWebDir       = $baseDir->getRealPath().'/skeleton/site/web';
        $vhostDir         = $baseDir->getRealPath().'/vhosts';
        $templateDir      = $baseDir->getRealPath().'/templates';
        $siteDir          = $vhostDir.'/'.preg_replace('/^www\./', '', $site->getDomain());
        $siteWebDir       = $siteDir.'/web';
        $siteTemplateDir  = $siteDir.'/templates';
        
        $site->setRootDir($siteDir)
            ->setWebDir($siteWebDir)
            ->setTemplateDir($siteTemplateDir);
        
        if (!file_exists($siteDir)) {
            $process = new Process(sprintf('cp -r %s %s', $skelDir, $siteDir));
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }
        }

        file_put_contents($siteWebDir.'/config.php', str_replace(array(
            '[id]',
            '[domain]'
        ), array(
            $site->getId(),
            $site->getDomain(),
        ), file_get_contents($skelWebDir.'/config.php')));

        $this->getSiteManager()->save($site);
    }

    public function rebuildConfiguration(SiteInterface $site)
    {
        if ($site->getAliasOf()) {
            return $this;
        }
        foreach (array('vhost.apache.conf.twig', 'vhost.nginx.conf.twig', 'php.ini.twig', 'php-fpm.conf.twig') as $file){
            $compiledFileName = preg_replace('/\.twig$/', '', $file);
            $compiledFile = $this->getTemplateEngine()->render('SplicedCmsBundle:Config/site:'.$file, array(
                'site' => $site
            ));
            file_put_contents($site->getRootDir().'/conf/'.$compiledFileName, $compiledFile);
        }
    }

    /**
     *
     */
    public function rebuildGlobalConfiguration()
    {
        $sites = $this->getSiteManager()->getAllSites();
        
        foreach (array('apache.conf.twig', 'nginx.conf.twig', 'php.ini.twig', 'php-fpm.conf.twig') as $file){
            $compiledFileName = preg_replace('/\.twig$/', '', $file);
            
            $compiledFile = $this->getTemplateEngine()->render('SplicedCmsBundle:Config/base:'.$file, array(
                'sites' => $sites
            ));

            file_put_contents($this->getKernel()->getRootDir().'/../vhosts/'.$compiledFileName, $compiledFile);
        }
    }

    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @return SiteManager
     */
    protected function getSiteManager()
    {
        return $this->siteManager;
    }

    /**
     * @return \Symfony\Component\Templating\EngineInterface
     */
    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }
    
}