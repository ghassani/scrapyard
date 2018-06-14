<?php

namespace Miva\Migration\Database\Zen;

trait CategoryTrait
{

    /**
    * loadCategories
    * 
    * @return array - Category ID Indexed Array of Categories
    */
    public function getCategories()
    {
        $results =  $this->getConnection()
          ->createQueryBuilder()
          ->select(
            'c.categories_id AS id',
            'c.categories_image AS image',
            'c.parent_id',
            'c.categories_status AS active',
            'c.sort_order AS disp_order',
            'd.categories_name AS name',
            'd.categories_description AS description',
            'm.metatags_title AS page_title',
            'm.metatags_keywords AS meta_keywords',
            'm.metatags_description AS meta_description'
          )
          ->from('categories', 'c')
          ->leftJoin('c', 'categories_description', 'd', 'd.categories_id = c.categories_id AND d.language_id = 1')
          ->leftJoin('c', 'meta_tags_categories_description', 'm', 'c.categories_id = m.categories_id AND m.language_id = 1')
          ->orderBy('c.sort_order', 'ASC')
          ->addOrderBy('c.categories_id', 'ASC')
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }


    /**
     * getCategoryById
     * 
     * @param mixed $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryById($id)
    {
        $results =  $this->getConnection()
          ->createQueryBuilder()
          ->select(
            'c.categories_id AS id',
            'c.categories_image AS image',
            'c.parent_id',
            'c.categories_status AS active',
            'c.sort_order AS disp_order',
            'd.categories_name AS name',
            'd.categories_description AS description',
            'm.metatags_title AS page_title',
            'm.metatags_keywords AS meta_keywords',
            'm.metatags_description AS meta_description'
          )
          ->from('categories', 'c')
          ->leftJoin('c', 'categories_description', 'd', 'd.categories_id = c.categories_id AND d.language_id = 1')
          ->leftJoin('c', 'meta_tags_categories_description', 'm', 'c.categories_id = m.categories_id AND m.language_id = 1')
          ->where('c.categories_id = :id')
          ->setParameter('id', $id)
          ->execute()
          ->fetchAll(); 

        $return = array();

        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }
}