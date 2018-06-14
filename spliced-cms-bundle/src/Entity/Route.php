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

/**
 * Route
 */
class Route
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $pageId;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $requestPath;
    /**
     * @var string
     */
    private $targetPath;
    /**
     * @var array
     */
    private $parameters = array();
    /**
     * @var SiteInterface
     */
    private $site;
    /**
     * @ORM\ManyToOne(targetEntity="ContentPage", inversedBy="routes")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     **/
    private $contentPage;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $requestPath
     */
    public function setRequestPath($requestPath)
    {
        $this->requestPath = $requestPath;
        return $this;
    }
    /**
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $contentPage
     */
    public function setContentPage($contentPage)
    {
        $this->contentPage = $contentPage;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getContentPage()
    {
        return $this->contentPage;
    }
    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    /**
     * @param \Spliced\Bundle\CmsBundle\Entity\Site $site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }
    /**
     * @return \Spliced\Bundle\CmsBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * @param string $targetPath
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;
        return $this;
    }
    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }
    /**
     * @param string $pageId
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }
    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->pageId;
    }
} 