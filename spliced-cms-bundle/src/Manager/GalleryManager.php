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

use Spliced\Bundle\CmsBundle\Entity\GalleryItem;
use Spliced\Bundle\CmsBundle\Model\GalleryItemInterface;
use Spliced\Bundle\CmsBundle\Model\SiteInterface;
use Symfony\Component\Finder\Finder;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Doctrine\ORM\EntityManager;

/**
 * GalleryManager
 */
class GalleryManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var \Imagine\Image\ImagineInterface
     */
    protected $imagine;
    
    /**
     * @var SiteManager
     */
    protected $siteManager;
    
    /**
     * @var array
     */
    protected $options = array(
        'allowed_file_types' => array('jpg','jpeg','png','gif'),
        'thumbnail_width' => 175,
        'thumbnail_height' => 150,
    );

    /**
     * store for cached file path names
     *
     * @var array
     */
    protected $cache = array();

    /**
     * Constructor
     *
     * @param ImagineInterface $imagine
     * @param array $options
     */
    public function __construct(SiteManager $siteManager, EntityManager $em, ImagineInterface $imagine, array $options = array())
    {
        $this->em  = $em;
        $this->imagine  = $imagine;
        $this->siteManager  = $siteManager;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * syncAll
     *
     * @return $this
     */
    public function syncAll()
    {
        foreach ($this->getSiteManager()->getAllSites() as $site) {
            $this->sync($site);
        }
        return $this;
    }

    /**
     * sync
     *
     * @param SiteInterface $site
     * @throws \InvalidArgumentException
     */
    public function sync(SiteInterface $site = null)
    {
        if ($site->getAliasOf()) {
            return $this;
        }
        $finder = new Finder();
        if (is_null($site)) {
            $site = $this->getSiteManager()->getCurrentAdminSite();
            if (!$site) {
                throw new \InvalidArgumentException('GalleryManager:sync was called with no site and the default site could not be loaded');
            }
        }
        $finder->files()
            ->name('/\.('.implode('|', $this->options['allowed_file_types']).')/')
            ->exclude(array('cache','bundles', 'logs'))
            ->in($site->getWebDir())
            ;
        foreach ($finder as $file) {
            $filePath = str_replace($site->getWebDir(), '', $file->getRealPath());
            if (empty($filePath)) {
                $filePath = '/';
            }
            $galleryItem = $this->getEntityManager()
                ->getRepository('SplicedCmsBundle:GalleryItem')
                ->findOneBy(array(
                    'site' => $site->getId(),
                    'filePath' => $filePath,
                    'fileName' => $file->getFilename()
                ));
            $image = $this->imagine->open($file->getRealPath());
            $imageDimensions = $image->getSize();
            if (!$galleryItem) {
                $galleryItem = new GalleryItem();
                $galleryItem->setSite($site)
                  ->setFileName($file->getFilename())
                  ->setFilePath($filePath)
                  ->setFileType($file->getExtension());
            }
            $galleryItem->setImageHeight($imageDimensions->getHeight())
                ->setImageWidth($imageDimensions->getWidth());
            if ($galleryItem->getId()) {
                $this->update($galleryItem);
            } else {
                $this->save($galleryItem);
            }
        }
        return $this;
    }
    /**
     * @param GalleryItemInterface $galleryItem
     */
    public function save(GalleryItemInterface $galleryItem)
    {
        $this->getEntityManager()->persist($galleryItem);
        $this->getEntityManager()->flush();
        return $this;
    }
    /**
     * @param GalleryItemInterface $galleryItem
     */
    public function update(GalleryItemInterface $galleryItem)
    {
        $this->getEntityManager()->persist($galleryItem);
        $this->getEntityManager()->flush();
        return $this;
    }
    /**
     * @param GalleryItemInterface $galleryItem
     */
    public function delete(GalleryItemInterface $galleryItem)
    {
        $this->getEntityManager()->remove($galleryItem);
        $this->getEntityManager()->flush();
        return $this;
    }
    /**
     * getCached
     *
     * @param GalleryItemInterface $galleryItem
     * @return bool|\SplFileInfo
     */
    public function getCached(GalleryItemInterface $galleryItem)
    {
        if ($this->hasCached($galleryItem)) {
            return new \SplFileInfo($this->getCachedPath($galleryItem));
        }
        return false;
    }
    /**
     * getCached
     *
     * @param GalleryItemInterface $galleryItem
     * @return bool|\SplFileInfo
     */
    public function hasCached(GalleryItemInterface $galleryItem)
    {
        return file_exists($this->getCachedPath($galleryItem));
    }
    /**
     * createCached
     *
     * @param GalleryItemInterface $galleryItem
     * @return \SplFileInfo
     */
    public function createCached(GalleryItemInterface $galleryItem)
    {
        $cachedPath = $this->getCachedPath($galleryItem);
        $file = new \SplFileInfo($galleryItem->getSite()->getWebDir().'/'.$galleryItem->getFilePath());
        if (!$file->isFile()) {
            die('TEST');
        }
        $image = $this->imagine->open($file->getRealPath());
        $fullDimensions = $image->getSize();
        $image->resize(new Box($this->options['thumbnail_width'], $this->options['thumbnail_height']))
            ->save($cachedPath);
        return $this;
    }
    /**
     * getCachedPath
     *
     * @param GalleryItemInterface $galleryItem
     * @param bool $relative - Return the path relative to the web directory
     * @return string
     */
    public function getCachedPath(GalleryItemInterface $galleryItem, $relative = false)
    {
        if (isset($this->cache[$galleryItem->getId()])) {
            $path = $this->cache[$galleryItem->getId()]['path'];
        } else {
            $fileNameHash = md5($galleryItem->getFilePath());
            $path = sprintf('%s/cache/gallery/%s.%s', $galleryItem->getSite()->getWebDir(), $fileNameHash, $galleryItem->getFileType());
            $this->cache[$galleryItem->getId()] = array(
                'hash' => $fileNameHash,
                'path' => $path
            );
        }
        if (true == $relative) {
            return str_replace($galleryItem->getSite()->getWebDir(), '', $path);
        }
        return $path;
    }
    /**
     * getImageCount
     *
     * @return int
     */
    public function getImageCount(SiteInterface $site)
    {
    }
    /**
     * getGalleryQuery
     *
     * @param SiteInterface $site
     * @return QueryBuilder
     */
    public function getGalleryQuery(SiteInterface $site)
    {
        $qb = $this->getEntityManager()
            ->getRepository('SplicedCmsBundle:GalleryItem')
            ->createQueryBuilder('g')
            ->select('g, s')
            ->leftJoin('g.site', 's')
            ->where('g.site = :site')
            ->setParameter('site', $site->getId());
        return $qb;
    }
    /**
     * getGallery
     *
     * @param SiteInterface $site
     * @param bool $syncFirst
     * @return array
     */
    public function getGallery(SiteInterface $site, $syncFirst = false)
    {
        if (true === $syncFirst) {
            $this->sync($site);
        }
        $images = $this->getGalleryQuery($site)
            ->getQuery()
            ->getResult();
        foreach ($images as $image) {
            $cachedImage = $this->getCached($image);
            if (!$cachedImage instanceof \SplFileInfo || !$cachedImage->isFile()) {
                $cachedImage = $this->createCached($image);
            }
            $image->setCached($cachedImage);
        }
        return $images;
        foreach ($images as $file) {
            if (stripos($file->getRealPath(), $this->cacheDir->getRealPath()) !== false) {
                continue; // finder isnt working correctly?
            }
            if (!$file->getRealPath()){
                continue;
            }
            $cacheName = md5_file($file->getRealPath()).'.'.$file->getExtension();
            $cachePath = $this->cacheDir->getRealPath().'/'.$cacheName;
            $image = $this->imagine->open($file->getRealPath());
            $fullDimensions = $image->getSize();
            if (!file_exists($cachePath)){
                $image->resize(new Box($this->options['thumbnail_width'], $this->options['thumbnail_height']))
                    ->save($cachePath);
                $cachedDimensions = $image->getSize();
            } else {
                $cachedImage = $this->imagine->open($cachePath);
                $cachedDimensions = $cachedImage->getSize();
            }
            $cachedFile = new \SplFileInfo($cachePath);
            $return[] = array(
                'original' => $file,
                'cache' => $cachedFile,
                'width' => $fullDimensions->getWidth(),
                'height' => $fullDimensions->getHeight(),
                'cache_width' => $cachedDimensions->getWidth(),
                'cache_height' => $cachedDimensions->getHeight(),
            );
        }
        return $return;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }
    /**
     * @return \Imagine\Image\ImagineInterface
     */
    protected function getImagine()
    {
        return $this->imagine;
    }
    /**
     * @return \Spliced\Bundle\CmsBundle\Manager\SiteManager
     */
    protected function getSiteManager()
    {
        return $this->siteManager;
    }
}