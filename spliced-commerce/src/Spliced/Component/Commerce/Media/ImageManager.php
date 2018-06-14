<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Media;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Spliced\Component\Commerce\Model\ProductImage;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Imagine\Gd\Image;

/**
 * ImageManager
 * 
 * Handles caching and resizing of product images
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ImageManager
{
    /** Imagine Adapter. Handles Image Changes, Resizing, etc */
    protected $adapter;
    
    /** Filesystem */
    protected $filesystem;
    
    /** $defaultResizeOptions */
    protected $defaultResizeOptions = array(
        'quality' => 75,
    );

    /**
     * Constructor
     *
     * @param HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel         = $kernel;
        $this->adapter         = new Imagine();
        $this->filesystem     = new Filesystem();
        
        $this->cacheDir =  new \SplFileInfo($kernel->getRootDir().'/../web/catalog/media/cache/');
        $this->webDir   =  new \SplFileInfo($this->kernel->getRootDir().'/../web/');
    }

    /**
     * getCacheDir
     * 
     * @param bool  $asSplObject
     *  
     * @return string|\SplFileInfo
     */
    public function getCacheDir($asSplObject = false)
    {
        if($asSplObject === true){
            return $this->cacheDir;
        }
        return $this->cacheDir->getRealPath().DIRECTORY_SEPARATOR;
    }
    
    /**
     * getWebDir
     *
     * @param bool  $asSplObject
     * 
     * @return string|\SplFileInfo
     */
    public function getWebDir($asSplObject = false)
    {
        if($asSplObject === true){
            return $this->webDir;
        }
        return $this->webDir->getRealPath().DIRECTORY_SEPARATOR;
    }
    
    /**
     * getKernel
     * 
     * @return HttpKernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }
    
    /**
     * getAdapter
     *
     * @return Imagine
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * resizeProductImage
     * 
     * Resizes and caches a product image
     * 
     * @param ProductImage|array $image
     * @param int $toWidt
     * @param int $toHeight
     * @param array $options
     */
    public function resizeProductImage($image, $toWidth, $toHeight, array $options = array())
    {
        if(!is_array($image)){
            if(!$image instanceof ProductImage){
                throw new \InvalidArgumentException('resizeProductImage Requires an Array of a ProductImage or instance of it');
            }
        }
        

        
        if(is_array($image)){
            $imageId = !isset($image['id']) ? isset($image['_id']) ? $image['_id'] : null : $image['id'];
            $fileName = $image['fileName'];
            $filePath = $image['filePath'];
        } else {
            $imageId = $image->getId();
            $fileName = $image->getFileName();
            $filePath = $image->getFilePath();
        }
                
        $cacheName = sprintf('product_image_%s_%s_%s-%s',
            $imageId,
            $toWidth,
            $toHeight,
            $fileName
        );
        
        if ($this->hasCache($cacheName)) {
            return $this->getCache($cacheName);
        }

        $options = array_merge($this->defaultResizeOptions, $options);

        $sourceFile = $this->getWebDir().$filePath.$fileName;

        if (!file_exists($sourceFile)) {
            throw new \Exception(sprintf('Image Source (%s) Does Not Exists For Resize to Take Place',$sourceFile));
        }

           $image = $this->getAdapter()
          ->open($sourceFile)
          ->resize(new Box($toWidth, $toHeight))
          ->save($this->getCacheDir().$cacheName, $options);

        return $this->getCache($cacheName);
    }

    /**
     * hasCache
     * 
     * @param string $cacheName
     */
    public function hasCache($cacheName)
    {
        return file_exists($this->getCacheDir().$cacheName);
    }

    /**
     * getCache
     * 
     * @param string $cacheName
     * @param bool $relativeToWeb
     */
    public function getCache($cacheName, $relativeToWeb = true)
    {
        if ($relativeToWeb === true) {
            return sprintf('/catalog/media/cache/%s',$cacheName);
        }

        return $this->getCacheDir().$cacheName;
    }
    
}
