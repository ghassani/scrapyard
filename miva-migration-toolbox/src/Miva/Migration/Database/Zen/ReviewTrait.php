<?php

namespace Miva\Migration\Database\Zen;

trait ReviewTrait
{
    /**
     * getReviews
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function getReviews()
    {
        $query = $this->getConnection()->prepare('
            SELECT 
                r.*, rd.*
            FROM 
                reviews r  
            LEFT JOIN reviews_description rd ON r.reviews_id = rd.reviews_id
        ');
        $query->execute(array());
        return $query->fetchAll();
    }
}