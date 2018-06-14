<?php

namespace Miva\Migration\Database\Miva;

trait CategoryTrait
{

    /**
     * getCategoryTitleImage
     * 
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryTitleImage($categoryId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CSSUI_CatTitle')
          ->where('cat_id = :cat_id')
          ->setParameter('cat_id', $categoryId)
          ->execute()
          ->fetch();
    }    

    /**
     * getCategories
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategories()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Categories')
          ->execute()
          ->fetchAll();
    }

    /**
     * loadCategoryByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Categories')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch();
    }


    /**
     * loadCategoryById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryById($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Categories')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch();
    }


    /**
     * insertCategory
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertCategory(array $category)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'Categories', 
            $category
        );          
    }

    /**
     * insertCategoryTitleImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertCategoryTitleImage(array $categoryTitleImage)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CSSUI_CatTitle', 
            $categoryTitleImage
        );          
    }

    /**
     * updateCategoryTitleImage
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCategoryTitleImage(array $categoryTitleImage)
    {
        if(!isset($categoryTitleImage['cat_id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'CSSUI_CatTitle', 
            $categoryTitleImage, 
            array('cat_id' => $categoryTitleImage['cat_id'])
        );     
    }


    /**
     * updateCategory
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCategory(array $category)
    {
        if(!isset($category['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'Categories', 
            $category, 
            array('id' => $category['id'])
        );      
    }
    
}