<?php

namespace Miva\Migration\Database\Miva;

trait HdftTrait
{
    /**
     * getProductHdft
     * 
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductHdft($productId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CSSUI_Product_HDFT')
          ->where('product_id = :product_id')
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getProductHdft
     * 
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductHdft(array $productHdft)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CSSUI_Product_HDFT', 
            $productHdft
        );        
    }


    /**
     * getCategoryHdft
     * 
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryHdft($categoryId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CSSUI_Category_HDFT')
          ->where('cat_id = :cat_id')
          ->setParameter('cat_id', $categoryId)
          ->execute()
          ->fetch(); 
    }

    public function insertCategoryHdft(array $categoryHdft)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CSSUI_Category_HDFT', 
            $categoryHdft
        );        
    }

}