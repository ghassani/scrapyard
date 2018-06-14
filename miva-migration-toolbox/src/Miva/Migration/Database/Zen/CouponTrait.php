<?php

namespace Miva\Migration\Database\Zen;

trait CouponTrait
{

    /**
     * getCoupons
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getCoupons()
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('coupons', 'c')
          ->leftJoin('c', 'coupons_description', 'd', 'c.coupon_id = d.coupon_id')
          ->execute()
          ->fetchAll();
    }   



    /**
     * getCouponRestrictions
     * 
     * @param mixed $couponId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCouponRestrictions($couponId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from('coupon_restrict')
          ->where('coupon_id = :couponId')
          ->setParameter('couponId', $couponId)
          ->execute()
          ->fetchAll();
    }   

}