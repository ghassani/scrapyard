<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductImage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_image")
 * @ORM\Entity()
 */
class ProductImage
{
    
     /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="label", type="string", nullable=false)
     */
    protected $label;
    
    /**
     * @ORM\Column(name="is_main", type="boolean", nullable=true)
     */
    protected $isMain;
    
    /**
     * @ORM\Column(name="file_name", type="string", nullable=false)
     */
    protected $fileName;
    
    /**
     * @ORM\Column(name="file_path", type="string", nullable=false)
     */
    protected $filePath;
    
    /**
     * @ORM\Column(name="file_md5", type="string", nullable=true)
     */
    protected $fileMd5;
    
    /**
     * @ORM\Column(name="file_type", type="string", nullable=true)
     */
    protected $fileType;
    
    /** @var File $uploadedImage */
    protected $uploadedImage;
    
    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getLabel
     *
     * @return string
    */
    public function getLabel()
    {
    	return $this->label;
    }

    /**
     * setLabel
     *
     * @param string label
     *
     * @return self
    */
    public function setLabel($label)
    {
	    $this->label = $label;
	    return $this;
    }
    
    
    /**
     * setIsMain
     *
     * @param boolean $isMain
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
        return $this;
    }
    
    /**
     * Get isMain
     *
     * @return boolean
     */
    public function getIsMain()
    {
        return $this->isMain;
    }
    
    
    /**
     * setFileName
     *
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    
    /**
     * getFileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * setFilePath
     *
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }
    
    /**
     * getFilePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    /**
     * setFileMd5
     *
     * @param string $fileMd5
     */
    public function setFileMd5($fileMd5)
    {
        $this->fileMd5 = $fileMd5;
        return $this;
    }
    
    /**
     * getFileMd5
     *
     * @return string
     */
    public function getFileMd5()
    {
        return $this->fileMd5;
    }
    
    /**
     * setFileType
     *
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        return $this;
    }
    
    /**
     * getFileType
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }
    
    /**
     * getFullPath
     * 
     * @return string
     */
    public function getFullPath()
    {
        return $this->getFilePath().$this->getFileName();
    }
    
    /**
     * 
     */
    public function getUploadedImage()
    {
        return $this->uploadedImage;
    }
    
    public function setUploadedImage($uploadedImage)
    {
        $this->uploadedImage = $uploadedImage;
        return $this;
    }
    
    /**
     * getProduct
     *
     * @return ProductInterface
    */
    public function getProduct()
    {
    	return $this->product;
    }

    /**
     * setProduct
     *
     * @param ProductInterface product
     *
     * @return self
    */
    public function setProduct(ProductInterface $product)
    {
	    $this->product = $product;
	    return $this;
    }
    
}