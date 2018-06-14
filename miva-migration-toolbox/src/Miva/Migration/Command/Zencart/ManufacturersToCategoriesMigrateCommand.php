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
* Migrates Manufacturer Data From Zen Cart Database to Miva Merchant Database As Categories
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ManufacturersToCategoriesMigrateCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-manufacturers-to-categories')
            ->setDescription('Migrates Manufacturer Data From Zen Cart Database to Miva Merchant Database As Categories')
            ->addOption('parent-category', null, InputOption::VALUE_OPTIONAL, 'Optionally Specify a Parent Category Code to Put All Under', null)
            ->addOption('code-prefix', null, InputOption::VALUE_OPTIONAL, 'An optional prefix to prepend to the codeified name', null)
            ->addOption('code-suffix', null, InputOption::VALUE_OPTIONAL, 'An optional suffix to append to the codeified name', null)
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $parentCategory = $this->getHelper('dialog')->askAndValidate(
            $output,
            'Optionally enter a category code to make all manufacturers children of: (default: none)',
            function ($v) {
                return $v;
            },
            false,
            null
        );

        $codePrefix = $this->getHelper('dialog')->askAndValidate(
            $output,
            'Optionally enter a string to prepend to the generated code: (default: none)',
            function ($v) {
                return $v;
            },
            false,
            null
        );

        $codeSuffix = $this->getHelper('dialog')->askAndValidate(
            $output,
            'Optionally enter a string to append to the generated code: (default: none)',
            function ($v) {
                return $v;
            },
            false,
            null
        );

        $input->setOption('parent-category', $parentCategory);
        $input->setOption('code-prefix', $codePrefix);
        $input->setOption('code-suffix', $codeSuffix);
    }
    
    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $metaKeywordName = $this->mivaQuery->getMetaName('keywords');
        $metaDescriptionName = $this->mivaQuery->getMetaName('description');
        $parentCategory = $input->getOption('parent-category');
    	$codePrefix = $input->getOption('code-prefix');
    	$codeSuffix = $input->getOption('code-suffix');

    	if($parentCategory){
    		$parentCategory = $this->mivaQuery->getCategoryByCode($parentCategory);
    		if(!$parentCategory){
    			$this->writeLn(sprintf('A parent category code of %s was specified but the category does not exist.', $input->getOption('parent-category')));
    			return static::COMMAND_ERROR;
    		}
    	}
        
    	$manufacturers = $this->zenQuery->getManufacturers();

    	$currentStoreKeyIncrement = $this->mivaQuery->getStoreKey('Categories') + 1;
    	$displayOrder = 0;
    	foreach($manufacturers as $manufacturer) {
    		$categoryCode = codeify($codePrefix.' '.$manufacturer['name'].' '.$codeSuffix);

            $targetCategory = $this->mivaQuery->getCategoryByCode($categoryCode);

            if($targetCategory) {
                $isNew = false;
                $this->writeLn(sprintf('Updating Manuacturer Category With ID %s And Code %s %s', 
                    $targetCategory['id'], 
                    $categoryCode, 
                    $parentCategory ? 'Under '.$parentCategory['code'] : null
                ));
            } else {
                $isNew = true;
                $targetCategory = array();
                $this->writeLn(sprintf('Creating Manuacturer Category With ID %s And Code %s %s', 
                    $currentStoreKeyIncrement, 
                    $categoryCode, 
                    $parentCategory ? 'Under '.$parentCategory['code'] : null
                ));
            }

            $targetCategory = array_merge($targetCategory, array(
                'id' => isset($targetCategory['id']) && $targetCategory['id'] > 0 ? $targetCategory['id'] : $currentStoreKeyIncrement, 
                'parent_id' => $parentCategory ? $parentCategory['id'] : 0, 
                'agrpcount' => 0, 
                'disp_order' => $displayOrder, 
                'page_id' => 0, 
                'code' => $categoryCode, 
                'name' => $this->toUTF8($manufacturer['name']),  
                'active' => 1, 
            ));

            try{

                if($isNew){
                    $this->mivaQuery->insertCategory($targetCategory);
                } else {
                   $this->mivaQuery->updateCategory($targetCategory);
                }
            } catch(\Exception $e){
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            $categoryKeywordsMeta = $this->mivaQuery->getCategoryMeta($targetCategory['id'], $metaKeywordName['id']);

            if(!$categoryKeywordsMeta){
                $this->mivaQuery->insertCategoryMeta($targetCategory['id'], $metaKeywordName['id'], $this->toUTF8($manufacturer['meta_keywords']));
                $this->writeLn(sprintf('Updated Category Meta Keywords With ID %s', $targetCategory['id']));
            } else {
                $this->mivaQuery->updateCategoryMeta($targetCategory['id'], $metaKeywordName['id'], $this->toUTF8($manufacturer['meta_keywords']));
                $this->writeLn(sprintf('Updated Category Meta Keywords With ID %s', $targetCategory['id']));
            }

            $categoryDescriptionMeta = $this->mivaQuery->getCategoryMeta($targetCategory['id'], $metaDescriptionName['id']);
            if(!$categoryDescriptionMeta){
                $this->mivaQuery->insertCategoryMeta($targetCategory['id'], $metaDescriptionName['id'], $this->toUTF8($manufacturer['meta_description']));
                $this->writeLn(sprintf('Updated Category Meta Description With ID %s', $targetCategory['id']));
            } else {
                $this->mivaQuery->updateCategoryMeta($targetCategory['id'], $metaDescriptionName['id'], $this->toUTF8($manufacturer['meta_description']));
                $this->writeLn(sprintf('Updated Category Meta Description With ID %s', $targetCategory['id']));
            }
            
            // update store key increment
    		$this->mivaQuery->updateStoreKey('Categories', $currentStoreKeyIncrement);

            $currentStoreKeyIncrement++;
            $displayOrder++;

            // relate products assigned to this manufacturer to the category we create/updated
            $products = $this->zenQuery->getProductsByManufacturerId($manufacturer['id']);
            foreach($products as $_product){
            	$product = $this->mivaQuery->getProductById($_product['id']);

            	if(!$product){
            		$this->writeLn(sprintf('Could Not Load Product %s For Category Assignment', $_product['id']));
            		continue;
            	}

            	$existingRelation = $this->mivaQuery->getProductCategory($product['id'], $targetCategory['id']);

            	if(!$existingRelation){
            		$this->writeLn(sprintf('Assigning Category %s to Product %s', $targetCategory['id'], $product['id']));
            		$this->mivaQuery->insertProductCategory($product['id'], $targetCategory['id'], $targetCategory['disp_order']);
            	} else {
            		$this->writeLn(sprintf('Category %s Already Assigned to Product %s', $targetCategory['id'], $product['id']));
            	}
            }
    	}    	

        $this->writeLn('Operation Completed.');
    }
}