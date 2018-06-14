<?php

namespace Miva\Migration\Database\Miva;

trait VikingCodersAffiliateTrait
{

    /**
     * insertVikingCoderAffiliate
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertVikingCoderAffiliate(array $affiliate)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'brokaffil_affiliates1', 
            $affiliate
        );     
    }

    /**
     * updateVikingCoderAffiliate
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateVikingCoderAffiliate(array $affiliate)
    {
        if(!isset($affiliate['affiliate'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'brokaffil_affiliates1', 
            $affiliate, 
            array(
                'affiliate' => $affiliate['affiliate'],
            )
        );       
    }

    /**
     * getVikingCoderAffiliateById
     * 
     * @param mixed $affiliateId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderAffiliateById($affiliateId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_affiliates1')
          ->where('affiliate = :id')
          ->setParameter('id', $affiliateId)
          ->execute()
          ->fetch(); 
    }

    /**
     * getVikingCoderAffiliateByCustomerId
     * 
     * @param mixed $customerId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderAffiliateByCustomerId($customerId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_affiliates1')
          ->where('cust_id = :id')
          ->setParameter('id', $customerId)
          ->execute()
          ->fetch(); 
    } 

    /**
     * getVikingCoderAffiliateByCode
     * 
     * @param mixed $affiliateCode Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderAffiliateByCode($affiliateCode)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_affiliates1')
          ->where('code = :code')
          ->setParameter('code', $affiliateCode)
          ->execute()
          ->fetch(); 
    } 


    /**
     * insertVikingCoderAffiliate2
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertVikingCoderAffiliate2(array $affiliate2)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'brokaffil_affiliates2', 
            $affiliate2
        );     
    }

    /**
     * updateVikingCoderAffiliate
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateVikingCoderAffiliate2(array $affiliate2)
    {
        if(!isset($affiliate['affiliate'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'brokaffil_affiliates2', 
            $affiliate2, 
            array(
                'affiliate' => $affiliate['affiliate'],
            )
        );       
    }

    /**
     * getVikingCoderAffiliate2ById
     * 
     * @param mixed $affiliateId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderAffiliate2ById($affiliateId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_affiliates2')
          ->where('affiliate = :id')
          ->setParameter('id', $affiliateId)
          ->execute()
          ->fetch(); 
    }



/**
     * insertVikingCoderAffiliatePayset
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertVikingCoderAffiliatePayset(array $affiliatePayset)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'brokaffil_affpayset', 
            $affiliatePayset
        );     
    }

    /**
     * updateVikingCoderAffiliatePayset
     * 
     * @param mixed \array affiliatePayset.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateVikingCoderAffiliatePayset(array $affiliatePayset)
    {
        if(!isset($affiliatePayset['affiliate'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'brokaffil_affpayset', 
            $affiliatePayset, 
            array(
                'affiliate' => $affiliatePayset['affiliate'],
            )
        );       
    }
    
    public function deleteVikingCoderAffiliate(array $affiliate)
    {
        if(!isset($affiliate['affiliate'])||empty($affiliate['affiliate'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->delete(
            $this->tablePrefix.'brokaffil_affiliates1', 
            array(
                'affiliate' => $affiliate['affiliate'],
            )
        );       
    }


    /**
     * getVikingCoderAffiliatePayset
     * 
     * @param mixed $affiliateId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderAffiliatePayset($affiliateId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_affpayset')
          ->where('affiliate = :id')
          ->setParameter('id', $affiliateId)
          ->execute()
          ->fetch(); 
    }


    /**
     * getVikingCoderDefaultPayset
     * 
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getVikingCoderDefaultPayset()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'brokaffil_payset')
          ->execute()
          ->fetch(); 
    }

}