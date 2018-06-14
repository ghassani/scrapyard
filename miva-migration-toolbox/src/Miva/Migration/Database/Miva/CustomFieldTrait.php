<?php

namespace Miva\Migration\Database\Miva;

trait CustomFieldTrait
{

    /**
     * getProductCustomFields
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCustomFields()
    {

        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_ProdFields')
          ->execute()
          ->fetchAll();

        $return = array();

        foreach($results as $row){
            $return[$row['code']] = $row;
        }

        return $return;
    }


    /**
     * getProductCustomField
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCustomField($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_ProdFields')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }

    /**
     * getProductCustomFieldValue
     * 
     * @param mixed $fieldId   Description.
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCustomFieldValue($fieldId, $productId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_ProdValues')
          ->where('field_id = :field_id')
          ->andWhere('product_id = :product_id')
          ->setParameter('field_id', $fieldId)
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getProductCustomFieldValueByCode
     * 
     * @param mixed $fieldCode Description.
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductCustomFieldValueByCode($fieldCode, $productId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_ProdValues', 'pv')
          ->innerJoin('pv', $this->tablePrefix.'CFM_ProdFields', 'pf')
          ->where('pf.code = :code')
          ->andWhere('pv.product_id = :product_id')
          ->setParameter('code', $fieldCode)
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    }


    /**
     * insertProductCustomFieldValue
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertProductCustomFieldValue(array $customFieldValue)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CFM_ProdValues', 
            $customFieldValue
        );        
    }

    /**
     * updateProductCustomFieldValue
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateProductCustomFieldValue(array $customFieldValue)
    {

        if(!isset($customFieldValue['field_id']) || !isset($customFieldValue['product_id'])){
            throw new \Exception('Update requires field_id and product_id');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'CFM_ProdValues', 
            $customFieldValue, 
            array(
                'field_id' => $customFieldValue['field_id'],
                'product_id' => $customFieldValue['product_id']
            )
        );       
    }

    /**
     * getCategoryCustomFields
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryCustomFields()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_CatFields')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $row){
            $return[$row['code']] = $row;
        }

        return $return;
    }


    /**
     * getCategoryCustomField
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryCustomField($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_CatFields')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }
    
    /**
     * getCategoryCustomFieldValue
     * 
     * @param mixed $fieldId   Description.
     * @param mixed $categoryId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryCustomFieldValue($fieldId, $categoryId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_CatValues')
          ->where('field_id = :field_id')
          ->andWhere('cat_id = :cat_id')
          ->setParameter('field_id', $fieldId)
          ->setParameter('cat_id', $categoryId)
          ->execute()
          ->fetch(); 
    }

  /**
     * insertProductCustomFieldValue
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertCategoryCustomFieldValue(array $customFieldValue)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'CFM_CatValues', 
            $customFieldValue
        );        
    }

    /**
     * updateCategoryCustomFieldValue
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCategoryCustomFieldValue(array $customFieldValue)
    {

        if(!isset($customFieldValue['field_id']) || !isset($customFieldValue['cat_id'])){
            throw new \Exception('Update requires field_id and cat_id');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'CFM_CatValues', 
            $customFieldValue, 
            array(
                'field_id' => $customFieldValue['field_id'],
                'cat_id'   => $customFieldValue['cat_id']
            )
        );       
    }

    /**
     * getOrderCustomFields
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderCustomFields()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_OrderFields')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $row){
            $return[$row['code']] = $row;
        }

        return $return;
    }

    /**
     * getOrderCustomField
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getOrderCustomField($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_OrderFields')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch();  
    }

    /**
     * getCustomerCustomFields
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomerCustomFields()
    {
        $results = $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_CustFields')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $row){
            $return[$row['code']] = $row;
        }

        return $return;
    }

    /**
     * getCustomerCustomField
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCustomerCustomField($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'CFM_CustFields')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch();  
    }
}