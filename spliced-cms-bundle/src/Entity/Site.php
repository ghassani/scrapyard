<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Bundle\CmsBundle\Model\ContentPageInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Site
 *
 * @ORM\Table(name="smc_site", uniqueConstraints={@ORM\UniqueConstraint(name="domain", columns={"domain"})}, indexes={@ORM\Index(name="alias_of", columns={"alias_of"})})
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CmsBundle\Repository\SiteRepository")
 */
class Site implements SiteInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var string
     */
    private $templateDir;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var Site
     */
    private $aliasOf;

    /**
     * @ORM\OneToMany(targetEntity="Site", mappedBy="aliasOf")
     **/
    private $aliases;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var Template
     */
    private $defaultPage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->aliases = new ArrayCollection();
    }

    /**
     * __toString
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getDomain();
    }

    /**
     * getId
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setAliasOf
     * 
     * @param SiteInterface $aliasOf
     * @return $this
     */
    public function setAliasOf(SiteInterface $aliasOf = null)
    {
        $this->aliasOf = $aliasOf;

        return $this;
    }

    /**
     * getAliasOf
     * 
     * @return mixed
     */
    public function getAliasOf()
    {
        return $this->aliasOf;
    }

    /**
     * setDomain
     * 
     * @param $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * getDomain
     * 
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * setDefaultPage
     * 
     * @param ContentPageInterface $defaultPage
     * @return $this
     */
    public function setDefaultPage(ContentPageInterface $defaultPage = null)
    {
        $this->defaultPage = $defaultPage;
        return $this;
    }

    /**
     * getDefaultPage
     * 
     * @return ContentPageInterface
     */
    public function getDefaultPage()
    {
        return $this->defaultPage;
    }

    /**
     * setIsActive
     * 
     * @param $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * getIsActive
     * 
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * isActive
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * setTemplateDir
     * 
     * @param string $templateDir
     * @return $this
     */
    public function setTemplateDir($templateDir)
    {
        $this->templateDir = $templateDir;
        return $this;
    }

    /**
     * getTemplateDir
     * 
     * @return string
     */
    public function getTemplateDir()
    {
        return $this->templateDir;
    }

    /**
     * setWebDir
     * 
     * @param string $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
        return $this;
    }

    /**
     * getWebDir
     * 
     * @return string
     */
    public function getWebDir()
    {
        return $this->webDir;
    }

    /**
     * setAliases
     * 
     * @param mixed $aliases
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * getAliases
     * 
     * @return mixed
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * setRootDir
     * 
     * @param string $rootDir
     * @return $this
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id'           => $this->getId(),
            'domain'       => $this->getDomain(),
            'webDir'       => $this->getWebDir(),
            'templateDir'  => $this->getTemplateDir(),
            'rootDir'      => $this->getRootDir(),
            'templateDir'  => $this->getTemplateDir(),
            'isActive'     => $this->getIsActive(),
        );
    }

    /**
     * @inheritDoc}
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }


}