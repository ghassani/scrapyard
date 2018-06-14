<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* ManufacturersToCategoriesMigrateCommand
*
* Migrates Zen Cart Reviews to Sebenza Ultimate Reviews Module
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ReviewsToSebenzaReviewsCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-reviews-to-sebenza')
            ->setDescription('Migrates Zen Cart Reviews to Sebenza Ultimate Reviews Module');
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
    }
    
    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $moduleExists = $this->mivaQuery->getModuleByCode('SS_REVIEWS');

        if(!$moduleExists){
            $continueAnyway = $this->getHelper('dialog')->askConfirmation(
                $output,
                '<question>It does not look like the Sebenza Reviews module is installed. Continue anyway?  (default: no) </question>', 
                false
            );
            if(!$continueAnyway) {
                return static::OPERATION_COMPLETE;
            }
        }

        $reviews = $this->zenQuery->getReviews();
        $ratingsCalculations = array();
        foreach($reviews as $review) {
            
            $zenProduct = $this->zenQuery->getProductById($review['products_id']);
            
            if(!$zenProduct){
                $this->writeLn(sprintf('Could Not Load Zen Product With ID %s', $review['products_id']));
            }

            $mivaProduct = $this->mivaQuery->getProductById($review['products_id']);

            if(!$mivaProduct){
                $this->writeLn(sprintf('Could Not Load Miva Product With ID %s', $review['products_id']));
            }

            $targetReview = $this->mivaQuery->getSebenzaReviewById($review['reviews_id']);

            
            if(!$targetReview){
                $isNew = true;
                $targetReview = array();
                $this->writeLn(sprintf('Creating Review ID %s For Product ID %s', $review['reviews_id'], $review['products_id']));
            } else {
                $isNew = false;
                $this->writeLn(sprintf('Updating Review ID %s For Product ID %s', $review['reviews_id'], $review['products_id']));
            }

            $createdAt = new \DateTime($review['date_added']);

            $customer = $this->mivaQuery->getCustomer($review['customers_id']);
           
            $targetReview = array_merge($targetReview, array(
                'id' => $review['reviews_id'],
                'product_id' => $review['products_id'],
                'dyn_time'   => $createdAt->getTimestamp(),  
                'approved'   => $review['status'],      
                's_email'    => $customer ? $this->toUTF8($customer['ship_email']) : null,  
                'rating'     => $review['reviews_rating'],
                'review'     => $this->toUTF8($review['reviews_text']),
                'title'      => null,  
                'name'       => $this->toUTF8($review['customers_name']),
                'email'      => $customer ? $this->toUTF8($customer['ship_email']) : null,
                'city'       => $customer ? $this->toUTF8($customer['ship_city']) : null,
                'state'      => $customer ? $this->toUTF8($customer['ship_state']) : null,  
                'cntry'      => $customer ? $this->toUTF8($customer['ship_cntry']) : null,
            ));

            try{

                if($isNew){
                    $this->mivaQuery->insertSebenzaReview($targetReview);
                } else {
                   $this->mivaQuery->updateSebenzaReview($targetReview);
                }
            } catch(\Exception $e){
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            if(!isset($ratingsCalculations[$targetReview['product_id']])){
                $ratingsCalculations[$targetReview['product_id']] = array();
            }
            array_push($ratingsCalculations[$targetReview['product_id']], $targetReview['rating']);
        }


        foreach($ratingsCalculations as $productId => $ratingsData) {            
            $average = (array_sum($ratingsData)/count($ratingsData));

            $this->writeLn(sprintf('Reviews for %s: %s - Average Rating: %s',
                $productId,
                count($ratingsData),
                $average
            ));

            $existingReviewRatings = $this->mivaQuery->getSebenzaProductReviewRating($productId);

            if(!$existingReviewRatings){
                $isNew = true;
                $existingReviewRatings = array();
            } else {
                $isNew = false;
            }

            $existingReviewRatings = array_merge($existingReviewRatings, array(
                'product_id' => $productId,
                'rating' => $average,
                'rcount' => count($ratingsData),
                'rdate' => time()
            ));

            if($isNew){
                $this->mivaQuery->insertSebenzaProductReviewRating($existingReviewRatings);
            } else {
                $this->mivaQuery->updateSebenzaProductReviewRating($existingReviewRatings);
            }
        }

        $this->writeLn('Operation Completed.');
    }
}