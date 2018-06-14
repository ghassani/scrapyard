<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
* CouponsToUltimateCouponsCommand
*
* Migrates Coupons to Ultimate Coupons Module
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class CouponsToUltimateCouponsCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-coupons-to-ultimate-coupons')
            ->setDescription('Migrates Coupons to Ultimate Coupons Module')
        ;
    }



    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $coupons = $this->zenQuery->getCoupons();

        foreach ($coupons as $coupon) {
            $restrictions = $this->zenQuery->getCouponRestrictions($coupon['coupon_id']);
            
            /*$this->writeLn(sprintf('Starting Coupon ID %s Code %s With %s Rules',
                $coupon['coupon_id'],
                $coupon['coupon_code'],
                count($restrictions)
            ));*/

            $existingCoupon = $this->mivaQuery->getUltimateCouponByCode($coupon['coupon_code']);
            
            $isNew = false;

            if (!$existingCoupon) {
                $existingCoupon = array();
                $isNew = true;
            }
            
            $expirationStart = new \DateTime($coupon['coupon_start_date'] ? $coupon['coupon_start_date'] : 'now');
            $expirationEnd = new \DateTime($coupon['coupon_expire_date'] ? $coupon['coupon_expire_date'] : 'now');

            if ($coupon['coupon_active'] == 'N') {
                $expirationStart = new \DateTime('now -5 days');
                $expirationEnd = new \DateTime('now');
            } else {
                $expirationStart = new \DateTime($coupon['coupon_start_date'] ? $coupon['coupon_start_date'] : 'now');
                $expirationEnd   = new \DateTime($coupon['coupon_expire_date'] ? $coupon['coupon_expire_date'] : 'now');
            }

            // Ultimate Coupon Types:
            // 1: Multiple Use
            // 2: One Per Customer
            // 3: One Time Use
            // 4: Group
            $couponType = 1;
            if ($coupon['uses_per_user'] == 1) {
                $couponType = 2; 
            }

            if ($coupon['uses_per_user'] == 0 && $coupon['uses_per_coupon'] == 0) {
                $couponType = 1; 
            }

           /* $productRestrictions  = array();
            $categoryRestrictions = array();

            foreach ($restrictions as $restriction) {
                if ($restriction['product_id']) {
                    // TODO
                } elseif ($restriction['category_id']) {
                    $products = $this->mivaQuery->getProductsByCanonicalCategory($restriction['category_id']);
                    
                    $productIds = array_map(function($v){
                        return $product['id'];
                    }, $products);

                    if($restriction['coupon_restrict'] == 'Y') {
                        // we need all products NOT matching the category
                        $productsToRestrict = $this->mivaQuery->getProductsByNotId($product)
                    } else {
                        // we just assign these products 
                    }
                }
            }*/

            $existingCoupon = array_merge($existingCoupon, array(
                'id'        => $coupon['coupon_id'],
                'code'      => $coupon['coupon_code'],
                'amount'    => $coupon['coupon_amount'],
                'exp_start' => $expirationStart->getTimestamp(),
                'exp_end'   => $expirationEnd->getTimestamp(),
                'tax'       => 0,
                'maxuse'    => 0, //decimal
                'maxdisc'   => 0.00, //decimal
                'minsub'    => $coupon['coupon_minimum_order'], //decimal
                'ctype'     => $couponType,
                'note'      => $this->toUTF8($coupon['coupon_description']),
                'affiliate' => 0,
                'excl_prod' => 0,
                'excl_pgrp' => 0,
                'excl_agrp' => 0,
                'instant'   => 0,
                'subexp'    => 0, //decimal
                'freeship'  => 0,
                'freeprod'  => 0,
                'multiple'  => 0,
                'minsubt'   => 0,
                'flatrate'  => 0, //decimal
            ));

            try {
                if ($isNew) {
                    $this->mivaQuery->insertUltimateCoupon($existingCoupon);
                } else {
                    $this->mivaQuery->updateUltimateCoupon($existingCoupon);
                }
            } catch (\Exception $e) {
                $this->writeLn(sprintf('Could Not %s Coupon. Exception: %s',
                    $isNew ? 'Create' : 'Update',
                    $e->getMessage()
                ));
            }
        }

        $this->writeLn('Operation Completed.');
    }
}