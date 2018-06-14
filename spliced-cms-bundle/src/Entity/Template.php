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
use Spliced\Bundle\CmsBundle\Model\TemplateInterface;
use Spliced\Bundle\CmsBundle\Model\TemplateVersionInterface;

/**
 * Template
 *
 * @ORM\Table(name="smc_template", indexes={@ORM\Index(name="version_id", columns={"version_id"})})
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CmsBundle\Repository\TemplateRepository")
 */
class Template implements TemplateInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=100, nullable=false, unique=true)
     */
    private $filename;

    /**
     * @var TemplateVersion
     *
     * @ORM\ManyToOne(targetEntity="TemplateVersion", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="version_id", referencedColumnName="id")
     * })
     */
    private $version;

    /**
     * @var TemplateVersion
     *
     * @ORM\ManyToOne(targetEntity="TemplateVersion", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="active_version_id", referencedColumnName="id")
     * })
     */
    private $activeVersion;

    /**
     * @ORM\OneToMany(targetEntity="TemplateExtension", mappedBy="template", cascade={"persist"})
     **/
    private $extensions;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set version
     *
     * @param TemplateVersionInterface $version
     * @return Template
     */
    public function setVersion(TemplateVersionInterface $version = null)
    {
        $version->setTemplate($this);

        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return TemplateVersionInterface
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $extensions
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param TemplateVersionInterface $activeVersion
     */
    public function setActiveVersion(TemplateVersionInterface $activeVersion)
    {
        $this->activeVersion = $activeVersion;
        return $this;
    }

    /**
     * @return TemplateVersionInterface
     */
    public function getActiveVersion()
    {
        return $this->activeVersion;
    }

    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'filename' => $this->getFilename(),
            'version' => $this->getVersion() ? $this->getVersion()->toArray() : array(),
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
