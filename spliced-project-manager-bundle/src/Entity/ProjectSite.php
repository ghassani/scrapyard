<?php

namespace Spliced\Bundle\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\CmsBundle\Entity\Site;
use Spliced\Bundle\ProjectManagerBundle\Model\ProjectStaffInterface;

/**
 * ProjectSite
 */
class ProjectSite implements ProjectStaffInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Project
     */
    private $project;


    /**
     * @var \Spliced\Bundle\CmsBundle\Model\SiteInterface
     */
    private $site;

    /**
     * @var Spliced\Bundle\CmsBundle\Model\TemplateInterface
     */
    private $template;

    /**
     * @var bool
     */
    private $isActive = true;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return \Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Spliced\Bundle\CmsBundle\Entity\Site $site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return SiteSpliced\Bundle\CmsBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param \Spliced\Bundle\ProjectManagerBundle\Entity\Template $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return \Spliced\Bundle\ProjectManagerBundle\Entity\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }



}
