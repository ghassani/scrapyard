<?php

namespace Miva\Migration\Database\Miva;

trait ProductTrait
{
    /**
     * getProducts
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getProducts()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->orderBy('code', "ASC")
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $p){
            $return[$p['id']] = $p;
        }

        return $return;
    }
    
    /**
     * getProducts
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductsOffset($offset, $limit)
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->orderBy('code', "ASC")
          ->setFirstResult($offset)
          ->setMaxResults($limit)
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $p){
            $return[$p['id']] = $p;
        }

        return $return;
    }
    
    /**
     * loadProducts
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCount()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('COUNT(DISTINCT id) AS count')
          ->from($this->tablePrefix.'Products')
          ->execute()
          ->fetch();

        return $results['count'];
    }
    
    /**
     * loadProductByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }

    /**
     * getProductsById
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductsById(array $ids)
    {
        array_walk($ids, 'intval');

        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->where('id IN(:ids)')
          ->setParameter('ids', implode(',', $ids))
          ->execute()
          ->fetchAll(); 
    }

    /**
     * getProductsByNotId
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductsByNotId(array $ids)
    {
        array_walk($ids, 'intval');

        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->where('id NOT IN(:ids)')
          ->setParameter('ids', implode(',', $ids))
          ->execute()
          ->fetchAll(); 
    }

    /**
     * getProductsByCanonicalCategory
     * 
     * @param mixed $c Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductsByCanonicalCategory($c)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->where('cancat_id = :cancatId')
          ->setParameter('cancatId', $cancatId)
          ->execute()
          ->fetchAll(); 
    }

    /**
     * loadProductById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductById($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'Products')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch(); 
    }

    /**
     * loadProductCategory
     * 
     * @param mixed $productId  Description.
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCategory($productId, $categoryId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CategoryXProduct')
          ->where('cat_id = :cat_id AND product_id = :product_id')
          ->setParameter('cat_id', $categoryId)
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    }

    /**
     * loadProductRelatedProduct
     * 
     * @param mixed $productId  Description.
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductRelatedProduct($productId, $relatedProductId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'RelatedProducts')
          ->where('product_id = :product_id AND relprod_id = :relprod_id')
          ->setParameter('product_id', $productId)
          ->setParameter('relprod_id', $relatedProductId)
          ->execute()
          ->fetch(); 
    }


    /**
     * insertProductCategory
     * 
     * @param mixed $productId    Description.
     * @param mixed $categoryId   Description.
     * @param int   $displayOrder Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductCategory($productId, $categoryId, $displayOrder = 0)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CategoryXProduct', 
            array(
                'cat_id' => $categoryId, 
                'product_id' => $productId, 
                'disp_order' => $displayOrder
            )
        );    
    }

    /**
     * insertProduct
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProduct(array $product)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'Products', 
            $product
        );       
    }

    /**
     * updateProduct
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateProduct(array $product)
    {
        if(!isset($product['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'Products', 
            $product, 
            array(
                'id' => $product['id'],
            )
        );   
    }

    /**
     * insertProductRelatedProduct
     * 
     * @param mixed $productId        Description.
     * @param mixed $relatedProductId Description.
     * @param int   $dispOrder        Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductRelatedProduct($productId, $relatedProductId, $dispOrder = 0)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'RelatedProducts', 
            array(
                'product_id' => $productId, 
                'relprod_id' => $relatedProductId, 
                'disp_order' => $dispOrder,
            )
        );      
    }
}