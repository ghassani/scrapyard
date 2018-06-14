<?php

namespace Miva\Migration\Database\Miva;

trait SebenzaReviewTrait
{

    /**
     * insertSebenzaReview
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertSebenzaReview(array $review)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'SS_REVIEWS_reviews', 
            $review
        );     
    }

    /**
     * updateSebenzaReview
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateSebenzaReview(array $review)
    {
        if(!isset($review['id'])){
            throw new \Exception('Update requires ID of record to update');
        }

        return $this->getConnection()->update(
            $this->tablePrefix.'SS_REVIEWS_reviews', 
            $review, 
            array(
                'id' => $review['id'],
            )
        );       
    }


    /**
     * getSebenzaReviewById
     * 
     * @param mixed $reviewId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSebenzaReviewById($reviewId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'SS_REVIEWS_reviews')
          ->where('id = :id')
          ->setParameter('id', $reviewId)
          ->execute()
          ->fetch(); 
    } 

    /**
     * insertSebenzaProductReviewRating
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function insertSebenzaProductReviewRating(array $reviewRatings)
    {
        return $this->getConnection()->insert(
            $this->tablePrefix.'SS_REVIEWS_ratings', 
            $reviewRatings
        );      
    }

    /**
     * updateSebenzaProductReviewRating
     * 
     * @param mixed \array Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateSebenzaProductReviewRating(array $reviewRatings)
    {
        if(!isset($reviewRatings['product_id'])){
            throw new \Exception('Update requires ID of record to update');
        }
        
        return $this->getConnection()->update(
            $this->tablePrefix.'SS_REVIEWS_ratings', 
            $reviewRatings, 
            array(
                'product_id' => $reviewRatings['product_id'],
            )
        );       
    }

    /**
     * getSebenzaProductReviewRating
     * 
     * @param mixed $productId Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSebenzaProductReviewRating($productId)
    {
        return $this->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->tablePrefix.'SS_REVIEWS_ratings')
          ->where('product_id = :product_id')
          ->setParameter('product_id', $productId)
          ->execute()
          ->fetch(); 
    } 


}