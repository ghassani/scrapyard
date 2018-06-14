<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\WebService;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Helper\Product\Price as ProductPriceHelper;
use Spliced\Component\Commerce\Model\ProductInterface;

/**
 * ProductSoapService
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSoapService
{
    /**
     * Constructors
     */
    public function __construct(ConfigurationManager $configurationManager, ProductPriceHelper $productPriceHelper, EntityManager $em)
    {
        $this->configurationManager = $configurationManager;
        $this->productPriceHelper = $productPriceHelper;
        $this->em = $em;
    }
    
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * getProductPriceHelper
     *
     * @return ProductPriceHelper
     */
    protected function getProductPriceHelper()
    {
        return $this->productPriceHelper;
    }
    
    /**
     * getProduct
     * 
     * @param int $orderId
     * 
     * @return array
     */
    public function getProduct($value, $isSku = false)
    {
        try{
            $order = $this->getEntityManager()
              ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
              ->getWebServiceQuery();
            
            if(!$isSku){
              $order->where('product.id = :id')
              ->setParameter('id', $value);
            } else {
                $order->where(' product.sku = :sku')
                ->setParameter('sku', $value);
            }
            $order = $order->getQuery()
              ->getSingleResult(Query::HYDRATE_ARRAY);
            
        } catch(NoResultException $e) {
            return json_encode(array('success' => false, 'error' => 'Product Not Found'));
        }
        
        return json_encode($order);
    }    
    
    /**
     * getProducts
     *
     * @param array $filters
     *
     * @return array
     */
    public function getProducts(array $filters)
    {
        $orderQuery = $this->getEntityManager()
          ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
          ->getWebServiceQuery();
            
        if(isset($filters['id'])){
            if(is_array($filters['id'])){
                $orderQuery->andWhere('product.id IN (:productIds)')
                  ->setParameter('productIds', $filters['id']);
            } else {
                $orderQuery->andWhere('product.id = :productId')
                 ->setParameter('productId', $filters['id']);
            }
        }
            
        if(isset($filters['sku'])){
            if(is_array($filters['sku'])){
                $orderQuery->andWhere('product.sku IN (:skus)')
                ->setParameter('skus', $filters['sku']);
            } else {
                $orderQuery->andWhere('product.sku = :sku')
                ->setParameter('sku', $filters['sku']);
            }
        }
        $orders = $orderQuery->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        return json_encode($orders);
    }
    
}