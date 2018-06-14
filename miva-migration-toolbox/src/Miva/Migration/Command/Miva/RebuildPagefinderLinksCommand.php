<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
* RebuildPagefinderLinksCommand
*
* Rebuild Magic Metal Pagefinder Module Links for Categories and Products
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class RebuildPagefinderLinksCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('rebuild-miva-pagefinder-links')
            ->setDescription('Rebuild Pagefinder Links')
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

        $pagefinderConfig = $this->mivaQuery->getPagefinderConfiguration();


        $products = $this->mivaQuery->getProducts();

        $spaceReplacementCharacter = $pagefinderConfig['filler'];

        foreach($products as $product) {
            if(!$product['active']){
                #$this->writeLn(sprintf('Skipping Inactive Product %s',$product['code']));
                continue;
            }

            $productPageLink = $this->mivaQuery->getPagefinderProductRoute($product['id']);

            $category = $this->mivaQuery->getCategoryById($product['cancat_id']);

            $pageName = null;
            if($category){
                $pageName = $category['name'].'/'.$product['name'];
            } else {
                $pageName = $product['name'];
            }

            $pageName = $this->pagefinderify($pageName, $spaceReplacementCharacter);

            if($productPageLink){
                $productPageLink['name'] = $pageName;
                

                try{
                    $this->mivaQuery->updatePagefinderProductRoute($productPageLink);
                } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
                    $pageName = $pageName.'-'.$product['id'];

                    $productPageLink['name'] = $pageName;
                    $this->mivaQuery->updatePagefinderProductRoute($productPageLink);
                } 

                $this->writeLn(sprintf('Updated Product Pagefinder Link For %s - %s',$product['code'], $pageName));

            } else {
                $productPageLink = array(
                    'product_id' => $product['id'],
                    'name' => $pageName
                );

                try{
                    $this->mivaQuery->insertPagefinderProductRoute($productPageLink);
                } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
                    $pageName = $pageName.'-'.$product['id'];

                    $productPageLink['name'] = $pageName;
                    $this->mivaQuery->insertPagefinderProductRoute($productPageLink);
                } 

                $this->writeLn(sprintf('Created Product Pagefinder Link For %s - %s',$product['code'], $pageName));
            }
        }


        $categories = $this->mivaQuery->getCategories();

        foreach($categories as $category) {
            if(!$category['active']){
                $this->writeLn(sprintf('Skipping Inactive Category %s',$category['code']));
                continue;
            }
        
            $pageName = $this->pagefinderify($category['name'], $spaceReplacementCharacter);

            $categoryPageLink = $this->mivaQuery->getPagefinderCategoryRoute($category['id']);

            if($categoryPageLink){
                $categoryPageLink['name'] = $pageName;
                
                
                try{
                    $this->mivaQuery->updatePagefinderCategoryRoute($categoryPageLink);
                } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
               
                    $pageName = $pageName.'-'.$category['id'];
                    $categoryPageLink['name'] = $pageName;
                    $this->mivaQuery->updatePagefinderCategoryRoute($categoryPageLink);
                }

                $this->writeLn(sprintf('Updated Category Pagefinder Link For %s - %s',$category['code'], $pageName));

            } else {
                $categoryPageLink = array(
                    'cat_id' => $category['id'],
                    'name' => $pageName
                );
                try{
                    $this->mivaQuery->insertPagefinderCategoryRoute($categoryPageLink);
                } catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e){
               
                    $pageName = $pageName.'-'.$category['id'];
                    $categoryPageLink['name'] = $pageName;
                    $this->mivaQuery->insertPagefinderCategoryRoute($categoryPageLink);
                }
                $this->writeLn(sprintf('Created Category Pagefinder Link For %s - %s',$category['code'], $pageName));
            }
        }

        $this->writeLn('Operation Completed.');
    }

    /**
     * pagefinderify
     * 
     * @param mixed $str    Description.
     * @param mixed $filler Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function pagefinderify($str, $filler)
    {
        return str_replace(array(' ','&','+','"',"'"), array($filler, 'and', 'plus', 'in', 'ft'), $str);
    }
}
