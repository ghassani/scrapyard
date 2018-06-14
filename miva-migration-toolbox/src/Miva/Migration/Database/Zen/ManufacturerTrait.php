<?php

namespace Miva\Migration\Database\Zen;

trait ManufacturerTrait
{

    /**
    * loadManufacturers
    * 
    * @return array - Manufacturer ID Indexed Array of Manufacturers
    */
    public function getManufacturers()
    {
        $query = $this->getConnection()->prepare('
            SELECT 
               m.manufacturers_id AS id,
               m.manufacturers_name As name,
               m.manufacturers_image AS image,
               i.manufacturers_url AS url,
               m2.metatags_title AS page_title,
               m2.metatags_keywords AS meta_keywords,
               m2.metatags_description AS meta_description
            FROM 
                manufacturers m
            LEFT JOIN 
                manufacturers_info i 
                    ON m.manufacturers_id = i.manufacturers_id
            LEFT JOIN 
                meta_tags_manufacturers_description m2
                    ON m.manufacturers_id = m2.manufacturers_id AND m2.language_id = 1      

            ORDER BY m.manufacturers_name ASC
        ');
        $query->execute();
        $results = $query->fetchAll();

        $return = array();
        foreach($results as $r){
            $return[$r['id']] = $r;
        }
        return $return;
    }

}