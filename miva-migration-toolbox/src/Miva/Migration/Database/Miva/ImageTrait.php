<?php

namespace Miva\Migration\Database\Miva;

trait ImageTrait
{

    /**
     * getImageTypes
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getImageTypes()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ImageTypes')
          ->execute()
          ->fetchAll(); 
    }

    /**
     * getImageTypeByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getImageTypeByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ImageTypes')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }


    /**
     * getProductImageByType
     * 
     * @param mixed $productId Description.
     * @param mixed $typeId    Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductImageByType($productId, $typeId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ProductImages')
          ->where('product_id = :product_id')
          ->andWhere('type_id = :type_id')
          ->setParameter('product_id', $productId)
          ->setParameter('type_id', $typeId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getProductImageByImageId
     * 
     * @param mixed $productId Description.
     * @param mixed $imageId   Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductImageByImageId($productId, $imageId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'ProductImages')
          ->where('product_id = :product_id')
          ->andWhere('image_id = :image_id')
          ->setParameter('product_id', $productId)
          ->setParameter('image_id', $imageId)
          ->execute()
          ->fetch(); 
    }

    /**
     * insertImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductImage(array $productImage)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'ProductImages', 
            $productImage
        );      
    }

    /**
     * updateProductImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value
     */
    public function updateProductImage(array $productImage)
    {
        if(!isset($productImage['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'ProductImages', 
            $productImage, 
            array(
                'id' => $productImage['id']
            )
        );      
    }

    /**
     * getImageByPath
     * 
     * @param mixed $imagePath Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getImageByPath($imagePath)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Images')
          ->where('image = :image')
          ->setParameter('image', $imagePath)
          ->execute()
          ->fetch(); 
    }

    /**
     * insertImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertImage(array $image)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'Images', 
            $image
        );      
    }

    /**
     * updateImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateImage(array $image)
    {
        if(!isset($image['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'Images', 
            $image, 
            array(
                'id' => $image['id']
            )
        );  
    }
}