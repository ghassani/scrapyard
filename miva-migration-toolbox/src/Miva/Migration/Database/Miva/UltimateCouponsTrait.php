<?php

namespace Miva\Migration\Database\Miva;

trait UltimateCouponsTrait
{


    /**
     * getUltimateCoupons
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getUltimateCoupons()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'SS_COUPON_coupons')
          ->execute()
          ->fetchAll(); 
    }

    /**
     * getUltimateCouponByCode
     * 
     * @param mixed $code Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getUltimateCouponByCode($code)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'SS_COUPON_coupons')
          ->where('code = :code')
          ->setParameter('code', $code)
          ->execute()
          ->fetch(); 
    }

    /**
     * getUltimateCouponById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getUltimateCouponById($id)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'SS_COUPON_coupons')
          ->where('id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetch(); 
    }

    /**
     * insertUltimateCoupon
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertUltimateCoupon(array $ultimateCoupon)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'SS_COUPON_coupons', 
            $ultimateCoupon
        );      
    }


    /**
     * updateUltimateCoupon
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateUltimateCoupon(array $ultimateCoupon)
    {
        if(!isset($ultimateCoupon['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'SS_COUPON_coupons', 
            $ultimateCoupon, 
            array(
                'id' => $ultimateCoupon['id']
            )
        );      
    }

}