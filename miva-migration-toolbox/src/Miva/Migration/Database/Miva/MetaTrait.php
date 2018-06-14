<?php

namespace Miva\Migration\Database\Miva;

trait MetaTrait
{


    /**
     * loadProductMeta
     * 
     * @param mixed $productId  Description.
     * @param mixed $metaNameId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductMeta($productId, $metaNameId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'METAProducts')
          ->where('product_id = :product_id AND name_id = :name_id')
          ->setParameter('product_id', $productId)
          ->setParameter('name_id', $metaNameId)
          ->execute()
          ->fetch(); 
    }

    /**
     * loadCategoryMeta
     * 
     * @param mixed $categoryId Description.
     * @param mixed $metaNameId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryMeta($categoryId, $metaNameId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'METACategories')
          ->where('cat_id = :cat_id AND name_id = :name_id')
          ->setParameter('cat_id', $categoryId)
          ->setParameter('name_id', $metaNameId)
          ->execute()
          ->fetch(); 
    }



    /**
     * getMetaName
     * 
     * @param mixed $metaName Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getMetaName($metaName)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'METANames')
          ->where('name = :name')
          ->setParameter('name', $metaName)
          ->execute()
          ->fetch(); 
    }

    /**
     * insertCategoryMeta
     * 
     * @param mixed $categoryId Description.
     * @param mixed $metaNameId Description.
     * @param mixed $metaValue  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertCategoryMeta($categoryId, $metaNameId, $metaValue)
    {
        $metaValue = str_replace(array("\n","\r","\t"), '', htmlentities($metaValue));

        return $this->getConnection()->insert(
            $this->tablePrefix.'METACategories', 
            array(
                'cat_id' => $categoryId, 
                'name_id' => $metaNameId, 
                'value' => null, 
                'value_long' => $metaValue, 
            )
        );     
    }


    /**
     * insertProductMeta
     * 
     * @param mixed $productId  Description.
     * @param mixed $metaNameId Description.
     * @param mixed $metaValue  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductMeta($productId, $metaNameId, $metaValue)
    {
        $metaValue = str_replace(array("\n","\r","\t"), '', htmlentities($metaValue));

        return $this->getConnection()->insert(
            $this->tablePrefix.'METAProducts', 
            array(
                'product_id' => $productId, 
                'name_id' => $metaNameId, 
                'value' => null, 
                'value_long' => $metaValue, 
            )
        );       
    }




    /**
     * updateCategoryMeta
     * 
     * @param mixed $categoryId Description.
     * @param mixed $metaNameId Description.
     * @param mixed $metaValue  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCategoryMeta($categoryId, $metaNameId, $metaValue)
    {
        $metaValue = str_replace(array("\n","\r","\t"), '', htmlentities($metaValue));

        return $this->getConnection()->update(
            $this->tablePrefix.'METACategories', 
            array(
                'value' => null, 
                'value_long' => $metaValue
            ), 
            array(
                'cat_id' => $categoryId,
                'name_id' => $metaNameId
            )
        );   
    }

    /**
     * updateProductMeta
     * 
     * @param mixed $productId  Description.
     * @param mixed $metaNameId Description.
     * @param mixed $metaValue  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateProductMeta($productId, $metaNameId, $metaValue)
    {
        $metaValue = str_replace(array("\n","\r","\t"), '', htmlentities($metaValue));
        
        return $this->getConnection()->update(
            $this->tablePrefix.'METAProducts', 
            array(
                'value' => null, 
                'value_long' => $metaValue
            ), 
            array(
                'product_id' => $productId,
                'name_id' => $metaNameId
            )
        );      
    }


    /** 
     * getMetaNameId
     * 
     * @param mixed $name Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getMetaNameId($name)
    {
        $result = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'METANames')
          ->where('name = :name')
          ->setParameter('name', $name)
          ->execute()
          ->fetch(); 

         return isset($result['id']) ? $result['id'] : false;   
    }

}