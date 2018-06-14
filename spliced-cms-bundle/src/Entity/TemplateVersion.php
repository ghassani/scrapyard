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
 * TemplateVersion
 *
 * @ORM\Table(name="smc_template_version", indexes={@ORM\Index(name="template_id", columns={"template_id"})})
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CmsBundle\Repository\TemplateVersionRepository")
 */
class TemplateVersion implements TemplateVersionInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=150, nullable=false)
     */
    private $label;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Template
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
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

	/**
	 * 
	 */
    public function __toString()
    {
    	if ($this->getLabel()) {
    		return $this->getLabel();
    	}

        return $this->getCreatedAt()->format('m/d/Y h:i a');
    }

    /**
     * Set content
     *
     * @param string $content
     * @return TemplateVersion
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return TemplateVersion
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return TemplateVersion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Set template
     *
     * @param Template $template
     * @return TemplateVersion
     */
    public function setTemplate(TemplateInterface $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return Template 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'content' => $this->getContent(),
            'label' => $this->getLabel(),
            'createdAt' => $this->getCreatedAt()->format('U'),
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
