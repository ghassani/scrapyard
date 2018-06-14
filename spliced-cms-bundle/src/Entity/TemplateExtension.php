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
use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Bundle\CmsBundle\Model\TemplateExtensionInterface;

/**
 * TemplateExtension
 *
 * @ORM\Table(
 * 	name="smc_template_extension",
 * 	indexes={
 * 		@ORM\Index(name="template_id", columns={"template_id"})
 * })
 * @ORM\Entity()
 */
class TemplateExtension implements TemplateExtensionInterface
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
     * @ORM\Column(name="extension_key", type="string", length=150)
     */
    private $extensionKey;
    /**
     * @var array
     *
     * @ORM\Column(name="settings", type="array")
     */
    private $settings;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    /**
     * @var TemplateVersion
     *
     * @ORM\ManyToOne(targetEntity="Template")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $template;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = 1;
    }
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
     * @param string $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
    /**
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * @param \Spliced\Bundle\CmsBundle\Entity\TemplateVersion $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    /**
     * @return \Spliced\Bundle\CmsBundle\Entity\TemplateVersion
     */
    public function getTemplate()
    {
        return $this->template;
    }
    /**
     * @param string $extensionKey
     */
    public function setExtensionKey($extensionKey)
    {
        $this->extensionKey = $extensionKey;
        return $this;
    }
    /**
     * @return string
     */
    public function getExtensionKey()
    {
        return $this->extensionKey;
    }
    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }
    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
