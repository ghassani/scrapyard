<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Symfony\Component\HttpFoundation\Session\Session;
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductFilterManager
 * 
 * Handles the filtering of products when viewing a collection of products.
 * Can be contextual to a specific category, or all products in general
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterManager
{
    /* Available List Modes by Default */
    const DISPLAY_MODE_GRID = 'grid';
    const DISPLAY_MODE_LIST = 'list';

    /** Session Tag Constants */
    const SESSION_TAG_PER_PAGE         = 'commerce.product.filter.per_page';
    const SESSION_TAG_ORDER_BY         = 'commerce.product.filter.order_by';
    const SESSION_TAG_DISPLAY_MODE     = 'commerce.product.filter.display_mode';
    const SESSION_TAG_SPECIFICATIONS   = 'commerce.product.filter.specifications';
    const SESSION_TAG_MANUFACTURERS    = 'commerce.product.filter.manufacturers';
    
    /** CategoryInterface|null */
    protected $category = null;

    /** Collection|null */
    protected $products = null;

    /** Collection|null */
    protected $specifications = null;
    
    /** Collection|null */
    protected $manufacturers = null;

    protected $specificationValueProductCounts = array();
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param Session $session
     */
    public function __construct(ConfigurationManager $configurationManager, Session $session)
    {
        $this->configurationManager = $configurationManager;
        $this->session = $session;
    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getSession
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * setPerPage
     *
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        if ($perPage > $this->configurationManager->get('commerce.category.filter.max_per_page', 50)) {
            $perPage = $this->configurationManager->get('commerce.category.filter.max_per_page', 50);
        }
        $this->session->set(static::SESSION_TAG_PER_PAGE, (int) $perPage);
        return $this;
    }

    /**
     * getPerPage
     *
     * @param  int|null $defaultReturn
     * @return int|null
     */
    public function getPerPage($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.max_per_page', 10);
        }

        return $this->session->get(static::SESSION_TAG_PER_PAGE, $defaultReturn);
    }

    /**
     * setOrderBy
     *
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->session->set(static::SESSION_TAG_ORDER_BY, $orderBy);
        return $this;
    }

    /**
     * getOrderBy
     *
     * @param string|null $defaultReturn
     */
    public function getOrderBy($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.default_order_by', 'price_asc');
        }

        return $this->session->get(static::SESSION_TAG_ORDER_BY, $defaultReturn);
    }

    /**
     * setDisplayMode
     *
     * @param string $displayMode
     */
    public function setDisplayMode($displayMode)
    {
        $this->session->set(static::SESSION_TAG_DISPLAY_MODE, $displayMode);

        return $this;
    }

    /**
     * getDisplayMode
     *
     * @param string|null $defaultReturn
     */
    public function getDisplayMode($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.default_display_mode', 'list');
        }

        return $this->session->get(static::SESSION_TAG_DISPLAY_MODE, $defaultReturn);
    }


     /**
      * getOrderByLabel
      * 
      * @return string
      */
     public function getOrderByLabel()
     {
         switch (strtolower($this->getOrderBy())) {
            default:
            case 'price_asc':
                return 'Price Ascending';
                break;
            case 'price_desc':
                return 'Price Descending';
                break;
            case 'name_asc':
                return 'Name Ascending';
                break;
            case 'name_desc':
                return 'Name Descending';
                break;
            case 'sku_asc':
                return 'Sku Ascending';
                break;
            case 'sku_desc':
                return 'Sku Descending';
                break;
         }
     }

     
     /**
      * setCategory
      * 
      * @param CategoryInterface|null
      */
     public function setCategory(CategoryInterface $category = null)
     {
         $this->category = $category;
         return $this;
     }
     
     /**
      * getCategory
      * 
      * @return CategoryInterface|null
      */
     public function getCategory()
     {
          return $this->category;
     }
     
     /**
      * getPaginationQuery
      * 
      * @return QueryBuilder
      */
     public function getPaginationQuery()
     {
        $productRepository = $this->configurationManager->getEntityManager()
           ->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT));

        $query = $productRepository->createQueryBuilder('product')
          ->select('product');

        // get our base query
        if( $this->getCategory()) {
             $query->innerJoin('product.categories', 'category')
                 ->where('category.category = :categoryId')
                 ->setParameter('categoryId', $this->getCategory()->getId());
        }

        $manufacturers = $this->getSelectedManufacturers($this->getCategory() ? $this->getCategory()->getId() : null);
        $specifications = $this->getSelectedSpecifications($this->getCategory() ? $this->getCategory()->getId() : null);

        if(count($manufacturers)){
            $query->andWhere('product.manufacturer IN (:manufacturers)')
               ->setParameter('manufacturers', $manufacturers);
        }

        if(count($specifications)){
            $query->leftJoin('product.specifications', 'specification');
            foreach($specifications as $optionId => $valueIds){
                if(empty($valueIds)||!count($valueIds)){
                    continue;
                }
                $query->andWhere('specification.option IN (:optionId) AND specification.value IN (:valueIds)')
                    ->setParameter('optionId', $optionId)
                    ->setParameter('valueIds', $valueIds);
            }
        }

        return $query;
     }
     
     /**
      * prepareView
      * 
      * Pre-load specifications and manufacturers before 
      * rendering the view
      */
     public function prepareView()
     {
         $this->getAvailableSpecifications();
         $this->getAvailableManufacturers();
         return $this;
     }
     
     /**
      * getAvailableSpecifications
      * 
      * Finds available specifications available to the current
      * filter context.
      * 
      * @return array
      */
    public function getAvailableSpecifications()
    {
        if(isset($this->specifications) && !is_null($this->specifications)){
            return $this->specifications;
        }
        $productIds = array_map(function($value){
            return $value['product_id'];
        },$this->getPaginationQuery()->select('partial product.{id}')->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR)
        );

        $productDataQuery = $this->configurationManager->getEntityManager()
            ->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT))
            ->createQueryBuilder('product')
            ->select('partial product.{id},partial specification.{id}, partial option.{id}, partial value.{id}');

        $productDataQuery->leftJoin('product.specifications', 'specification')
            ->leftJoin('specification.option', 'option')
            ->leftJoin('specification.value', 'value')
            ->andWhere('option.filterable = 1 AND product.id IN (:productIds)')
            ->setParameter('productIds', $productIds);

        $productData = $productDataQuery->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

        $filters = array(
            'products' => array(),
            'options' => array(),
            'values' => array()
        );

        $valueProductCounts = array();
        foreach($productData as $_productData){
            if(!in_array($_productData['product_id'], $filters['products'])){
                array_push($filters['products'],  $_productData['product_id']);
            }
            if(!in_array($_productData['option_id'], $filters['options'])){
                array_push($filters['options'],  $_productData['option_id']);
            }

            if(!in_array($_productData['value_id'], $filters['values'])){
                array_push($filters['values'],  $_productData['value_id']);
            }

            if (!isset($valueProductCounts[$_productData['option_id']][$_productData['value_id']])) {
                $valueProductCounts[$_productData['option_id']][$_productData['value_id']] = array();
            }
            $valueProductCounts[$_productData['option_id']][$_productData['value_id']][] = $_productData['product_id'];
        }

        $this->specificationValueProductCounts = $valueProductCounts;

        $specificationsQuery = $this->configurationManager->getEntityManager()
        ->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION))
        ->createQueryBuilder('option')
        ->select('option, values')
        ->leftJoin('option.values', 'values')
        ->innerJoin('option.productSpecifications', 'productSpecifications')
        ->where('productSpecifications.product IN (:productIds)')
        ->andWhere('option.id IN (:optionIds)')
        ->andWhere('values.id IN (:valueIds)')
        ->setParameters(array(
            'productIds' => $filters['products'],
            'optionIds' => $filters['options'],
            'valueIds' => $filters['values'],
        ));


         $this->specifications = $specificationsQuery->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
         return $this->specifications;
     }
     
     /**
      * getAvailableManufacturers
      *
      * Finds available manufacturers available to the current
      * filter context.
      * 
      * @return array
      */
     public function getAvailableManufacturers()
     {
         if(isset($this->manufacturers) && !is_null($this->manufacturers)){
             return $this->manufacturers;
         }
         
         // lets load, as an array, just all product specifications
         // matching the current criteria
         $products = $this->getPaginationQuery()
         ->getQuery()
         ->execute();

         $manufacturerIds = array();
         foreach($products as $product) {
             if($product->getManufacturer()){
                 $manufacturerIds[] = $product->getManufacturer()->getId();
             }
         }
         
         if (count($manufacturerIds)) {
	         $this->manufacturers = $this->configurationManager->getEntityManager()
	           ->getRepository($this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_MANUFACTURER))
	           ->createQueryBuilder('manufacturer')
	           ->where('manufacturer.id IN(:ids)')
	           ->setParameter('ids', $manufacturerIds)
	           ->getQuery()
	           ->getResult();
         }
         
         return $this->manufacturers;
     }
     
     /**
      * getSelectedSpecifications
      * 
      * @return array
      */
     public function getSelectedSpecifications($categoryId)
     {
         return  $this->session->get(static::SESSION_TAG_SPECIFICATIONS.'.'.$categoryId, array());
     }     

     /**
      * setSelectedSpecifications
      * 
      * @param array $specifications
      * @param string $categoryId
      */
     public function setSelectedSpecifications(array $specifications, $categoryId = null)
     {
         $this->session->set(static::SESSION_TAG_SPECIFICATIONS.'.'.(is_null($categoryId) ? 'all' : $categoryId), $specifications);
         return $this; 
     }
     
     /**
      * addSelectedSpecification
      * 
      * @param string $specificationId
      * @param string $value
      * @param string $categoryId
      */
     public function addSelectedSpecification($optionId, $valueId, $categoryId)
     {
         $options = $this->getSelectedSpecifications($categoryId);
         if(!isset($options[$optionId])){
             $options[$optionId] = array($valueId);
         } else if(!in_array($valueId, $options[$optionId])) {
             $options[$optionId][] = $valueId;
         }         
         $this->setSelectedSpecifications($options, $categoryId);
         return $this;
     }
     
     /**
      * removeSelectedSpecification
      * 
      * @param string $specificationId
      * @param string $value
      * @param string $categoryId
      */
     public function removeSelectedSpecification($optionId, $valueId, $categoryId = null)
     {
         $options = $this->getSelectedSpecifications($categoryId);

         foreach($options as $_optionId => &$valueIds){
             foreach($valueIds as $key => &$_valueId){
                 if($valueId == $_valueId){
                     unset($valueIds[$key]);
                 }
             }
         }
         $this->setSelectedSpecifications($options, $categoryId);
         return $this;
     }
     
     /**
      * getSelectedManufacturers
      * 
      * @return array
      */
     public function getSelectedManufacturers($categoryId = null)
     {
          return  $this->session->get(static::SESSION_TAG_MANUFACTURERS.'.'.(is_null($categoryId) ? 'all' : (int) $categoryId), array());
     }
     
     /**
      * setSelectedSpecifications
      * 
      * @param array $manufacturerIds
      * @param string $categoryId 
      */
     public function setSelectedManufacturers(array $manufacturerIds, $categoryId = null)
     {
         $this->session->set(static::SESSION_TAG_MANUFACTURERS.'.'.(is_null($categoryId) ? 'all' : (int) $categoryId), $manufacturerIds);
         return $this;
     }
      
     /**
      * addSelectedManufacturer
      * 
      * @param string $manufacturerId
      * @param string $categoryId
      */
     public function addSelectedManufacturer($manufacturerId, $categoryId = null)
     {
          $manufacturers = $this->getSelectedManufacturers($categoryId);
          if (!in_array($manufacturerId, $manufacturers)) {
              $manufacturers[] = $manufacturerId;
          }
          $this->setSelectedManufacturers($manufacturers, $categoryId);
          return $this;
     }
     
     /**
      * removeSelectedManufacturer
      * 
      * @param string $manufacturerId
      * @param string $categoryId
      */
     public function removeSelectedManufacturer($manufacturerId, $categoryId = null)
     {
         $manufacturers = $this->getSelectedManufacturers($categoryId);
         foreach ($manufacturers as $key => $_manufacturerId) {
             if ($_manufacturerId == $manufacturerId) {
                 unset($manufacturers[$key]);
             }
         }
         $this->setSelectedManufacturers($manufacturers, $categoryId);
         return $this;
     }

    /**
     *
     */
    public function getSpecificationValueProductCount($optionId, $valueId)
    {
        if(isset($this->specificationValueProductCounts[$optionId][$valueId])){
            return count(array_unique($this->specificationValueProductCounts[$optionId][$valueId]));
        }
        return 0;
    }

}