<?php

namespace Miva\Migration\Database\Miva;

trait PagefinderTrait
{

    /**
     * getPagefinderConfiguration
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderConfiguration()
    {
        $result = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinder')
          ->execute()
          ->fetch(); 

        return miva_structure_deserialize($result['data']);
    }


    /**
     * getPagefinderProductRoutes
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderProductRoutes()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinderProducts')
          ->orderBy('product_id', 'ASC')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $p){
            $return[$p['product_id']] = $p;
        }

        return $return;
    }

    /**
     * getPagefinderProductRoute
     * 
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderProductRoute($productId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinderProducts')
          ->where('product_id = :product_id')
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getPagefinderProductRouteByName
     * 
     * @param mixed $name Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderProductRouteByName($name)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinderProducts')
          ->where('name = :name')
          ->setParameter('name', $name)
          ->execute()
          ->fetch(); 
    }

    /**
     * insertPagefinderProductRoute
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertPagefinderProductRoute(array $productPage)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'PageFinderProducts', 
            $productPage
        );       
    }


    /**
     * updatePagefinderProductRoute
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePagefinderProductRoute(array $productPage)
    {
        if(!isset($productPage['product_id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'PageFinderProducts', 
            $productPage, 
            array(
                'product_id' => $productPage['product_id'],
            )
        );      
    }

    /**
     * getPagefinderCategoryRoutes
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderCategoryRoutes()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinderCats')
          ->orderBy('cat_id', 'ASC')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $p){
            $return[$p['cat_id']] = $p;
        }

    }

    /**
     * getPagefinderCategoryRoute
     * 
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getPagefinderCategoryRoute($categoryId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'PageFinderCats')
          ->where('cat_id = :cat_id')
          ->setParameter('cat_id', $categoryId)
          ->execute()
          ->fetch(); 
    }



    /**
     * insertPagefinderCategoryRoute
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertPagefinderCategoryRoute(array $categoryPage)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'PageFinderCats', 
            $categoryPage
        );    
    }


    /**
     * updatePagefinderCategoryRoute
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePagefinderCategoryRoute(array $categoryPage)
    {
        if(!isset($categoryPage['cat_id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'PageFinderCats', 
            $categoryPage, 
            array(
                'cat_id' => $categoryPage['cat_id'],
            )
        );            
    }
}