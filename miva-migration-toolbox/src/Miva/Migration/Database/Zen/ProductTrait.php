<?php

namespace Miva\Migration\Database\Zen;

trait ProductTrait
{

    /**
    * loadProducts
    * 
    * @return array - Product ID Indexed Array of Products
    */
    public function getProducts()
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                p.products_id AS id,
                p.products_quantity AS inventory,
                p.products_model AS code,
                p.products_image AS image,
                p.products_price AS price,
                p.products_weight AS weight, 
                p.products_status AS active,
                p.master_categories_id AS category_id,
                p.manufacturers_id AS manufacturer_id,
                d.products_name AS name,                
                d.products_description AS description,
                m.metatags_title AS page_title,
                m.metatags_keywords AS meta_keywords,
                m.metatags_description AS meta_description
            FROM 
                products p 
            LEFT JOIN 
                products_description d 
                    ON p.products_id = d.products_id AND d.language_id = 1 
            LEFT JOIN 
                meta_tags_products_description m 
                    ON p.products_id = m.products_id AND m.language_id = 1      
            ORDER BY p.products_model, p.products_id ASC
        ');
        $query->execute();
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }

    public function getProductById($id)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                p.products_id AS id,
                p.products_quantity AS inventory,
                p.products_model AS code,
                p.products_image AS image,
                p.products_price AS price,
                p.products_weight AS weight, 
                p.products_status AS active,
                p.master_categories_id AS category_id,
                p.manufacturers_id AS manufacturer_id,
                d.products_name AS name,                
                d.products_description AS description,
                m.metatags_title AS page_title,
                m.metatags_keywords AS meta_keywords,
                m.metatags_description AS meta_description
            FROM 
                products p 
            LEFT JOIN 
                products_description d 
                    ON p.products_id = d.products_id AND d.language_id = 1 
            LEFT JOIN 
                meta_tags_products_description m 
                    ON p.products_id = m.products_id AND m.language_id = 1    
            WHERE p.products_id = ?  
            ORDER BY p.products_model, p.products_id ASC
        ');

        $query->execute(array($id));
        return $query->fetch();
    }

    /**
     * loadProductsByManufacturerId
     * 
     * @param mixed $manufacturerId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getProductsByManufacturerId($manufacturerId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                p.products_id AS id,
                p.products_quantity AS inventory,
                p.products_model AS code,
                p.products_image AS image,
                p.products_price AS price,
                p.products_weight AS weight, 
                p.products_status AS active,
                p.master_categories_id AS category_id,
                p.manufacturers_id AS manufacturer_id,
                d.products_name AS name,                
                d.products_description AS description,
                m.metatags_title AS page_title,
                m.metatags_keywords AS meta_keywords,
                m.metatags_description AS meta_description
            FROM 
                products p 
            LEFT JOIN 
                products_description d 
                    ON p.products_id = d.products_id AND d.language_id = 1 
            LEFT JOIN 
                meta_tags_products_description m 
                    ON p.products_id = m.products_id AND m.language_id = 1     
            WHERE p.manufacturers_id = ? 
            ORDER BY p.products_model, p.products_id ASC
        ');
        $query->execute(array($manufacturerId));
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }


    /**
    * loadProductCategories
    * 
    * @return array - An array of Category IDs related to specified product 
    */
    public function getProductCategories($productId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
               categories_id AS id
            FROM 
                products_to_categories 
            WHERE products_id = ?
        ');
        $query->execute(array($productId));
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[] = $r['id'];
        }
        return $return;
    }

    /**
    * loadRelatedProducts
    * 
    */
    public function getRelatedProducts($productId)
    {
        $query = $this->getConnection()->prepare('
            SELECT 
               products_id AS product_id,
               xsell_id AS relprod_id,
               sort_order AS disp_order
            FROM 
                products_xsell 
            WHERE products_id = ?
        ');
        $query->execute(array($productId));
        return $query->fetchAll();
    }
}