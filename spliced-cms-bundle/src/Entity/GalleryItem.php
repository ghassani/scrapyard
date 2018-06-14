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

use Spliced\Bundle\CmsBundle\Model\GalleryItemInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;

/**
 * GalleryItem
 */
class GalleryItem implements GalleryItemInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var string
     */
    private $fileType;
    /**
     * @var string
     */
    private $imageWidth;
    /**
     * @var string
     */
    private $imageHeight;
    /**
     * @var SiteInterface
     */
    private $site;
    /**
     * @var \SplFileInfo|null
     */
    private $cached;
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
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }
    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        return $this;
    }
    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }
    /**
     * @param SiteInterface $site
     */
    public function setSite(SiteInterface $site)
    {
        $this->site = $site;
        return $this;
    }
    /**
     * @return SiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * @param string $imageHeight
     */
    public function setImageHeight($imageHeight)
    {
        $this->imageHeight = $imageHeight;
        return $this;
    }
    /**
     * @return string
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }
    /**
     * @param string $imageWidth
     */
    public function setImageWidth($imageWidth)
    {
        $this->imageWidth = $imageWidth;
        return $this;
    }
    /**
     * @return string
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }
    /**
     * @param null|\SplFileInfo $cached
     */
    public function setCached(\SplFileInfo $cached = null)
    {
        $this->cached = $cached;
        return $this;
    }
    /**
     * @return null|\SplFileInfo
     */
    public function getCached()
    {
        return $this->cached;
    }
    /**
     * @return \SplFileInfo
     */
    public function getFileInfo()
    {
        if (isset($this->fileInfo) && $this->fileInfo instanceof \SplFileInfo) {
            return $this->fileInfo;
        }
        $this->fileInfo = new \SplFileInfo($this->getSite()->getWebDir().'/'.$this->getFilePath());
        return $this->fileInfo;
    }
}
