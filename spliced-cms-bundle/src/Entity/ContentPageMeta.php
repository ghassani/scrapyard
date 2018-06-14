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

use Spliced\Bundle\CmsBundle\Model\ContentPageInterface;
use Spliced\Bundle\CmsBundle\Model\ContentPageMetaInterface;

/**
 * ContentPageMeta
 */
class ContentPageMeta implements ContentPageMetaInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $metaKey;

    /**
     * @var string
     */
    private $metaValue;

    /**
     * @var ContentPageInterface
     **/
    private $contentPage;

    /**
     * Set metaKey
     *
     * @param string $metaKey
     * @return ContentPageMeta
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    /**
     * Get metaKey
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaValue
     *
     * @param string $metaValue
     * @return ContentPageMeta
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    /**
     * Get metaValue
     *
     * @return string 
     */
    public function getMetaValue()
    {
        return $this->metaValue;
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
     * Set contentPage
     *
     * @param ContentPageInterface $contentPage
     * @return ContentPageMeta
     */
    public function setContentPage(ContentPageInterface $contentPage = null)
    {
        $this->contentPage = $contentPage;

        return $this;
    }

    /**
     * Get contentPage
     *
     * @return ContentPageInterface
     */
    public function getContentPage()
    {
        return $this->contentPage;
    }


    /**
     * @inheritDoc}
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'metaKey' => $this->getMetaKey(),
            'metaValue' => $this->getMetaValue(),
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
