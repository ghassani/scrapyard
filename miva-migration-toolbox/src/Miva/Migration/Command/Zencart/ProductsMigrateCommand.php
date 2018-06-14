<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
* ProductsMigrateCommand
*
* Migrates Product Data From Zen Cart Database to Miva Merchant Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductsMigrateCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-products')
            ->setDescription('Migrates Product Data From Zen Cart Database to Miva Merchant Database')
            ->addOption('include-inactive', null, InputOption::VALUE_OPTIONAL, 'Include inactive products in Zen. Defaults to false.', false)
            ->addOption('image-type', null, InputOption::VALUE_OPTIONAL, 'Assign images as an additonal image with set type.', null)
            ->addOption('base-target-image-path', null, InputOption::VALUE_OPTIONAL, 'Path Prefix for Images to Import into Full Sized Image and Additional Images.', 'graphics/00000001/')
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $importInactive = $this->getHelper('dialog')->askConfirmation($output,'<question>Import Inactive Zen Products? (default: no) </question>',false);

        $baseTargetImagePath = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the base path (from mm5 root) to the product images folder? (default: graphics/00000001/) </question>',
            function ($path) {
                return $path;
            },
            false,
            'graphics/00000001/'
        );

        $imageTypes = $this->mivaQuery->getImageTypes();
        $imageTypeChoices = array(0 => 'None');
        foreach($imageTypes as $it) {
            $imageTypeChoices[$it['code']] = $it['descrip'];
        }

        if(count($imageTypeChoices) > 1){
            $imageType = $this->getHelper('dialog')->select(
                $output,
                '<question>Assign the product image to which image type? (default: none)</question>',
                $imageTypeChoices,
                'none'
            );
            $input->setOption('image-type', $imageType);
        }

        $input->setOption('include-inactive', $importInactive);
        $input->setOption('base-target-image-path', $baseTargetImagePath);        
    }


    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseTargetImagePath = $input->getOption('base-target-image-path', 'graphics/00000001/');
        $imageTypeCode = $input->getOption('image-type');

        if($imageTypeCode){
            $imageType = $this->mivaQuery->getImageTypeByCode($imageTypeCode);
            if(!$imageType){
                $output->writeLn(sprintf('A image type of %s was specified but that image type does not exist.', $imageTypeCode));
                return static::OPERATION_ERROR;
            }
        }

        $products = $this->zenQuery->getProducts();

        $establishedCodes = array();

        $this->writeLn(sprintf('Loaded %s Products', count($products)));


        $metaKeywordName = $this->mivaQuery->getMetaName('keywords');
        $metaDescriptionName = $this->mivaQuery->getMetaName('description');

        foreach($products as $displayOrder => $product) {

            if(!$input->getOption('include-inactive') && !$product['active']){
                continue;
            }

            $productCode = $product['code'];
            if(empty($productCode)){
                $productCode = $product['id'];
                if(in_array($productCode, $establishedCodes)){
                    $output->writeLn(sprintf('<error>Code %s Already Exists!</error>', $product['id']));
                    return static::COMMAND_ERROR;
                }
            } else if(in_array($productCode, $establishedCodes)){
                $productCode .= $product['products_id'];
                if(in_array($productCode, $id)){
                    $output->writeLn(sprintf('<error>Code %s Already Exists!</error>', $product['code']));
                    return static::COMMAND_ERROR;
                }
            }

            $targetProduct = $this->mivaQuery->getProductById($product['id']);

            if($targetProduct) {
                $isNew = false;
                $this->writeLn(sprintf('Updating Product With ID %s And Code %s', $product['id'], $productCode));
            } else {
                $isNew = true;
                $targetProduct = array();
                $this->writeLn(sprintf('Creating Product With ID %s And Code %s', $product['id'], $productCode));
            }
           

            $productCategories = $this->zenQuery->getProductCategories($product['id']);

            if(!in_array($product['category_id'], $productCategories)){
                $productCategories[] = $product['category_id'];
            }

            $targetProduct = array_merge($targetProduct, array(
                'id' => $product['id'], 
                'catcount' => count($productCategories), 
                'cancat_id' => $product['category_id'], 
                'agrpcount' => 0, 
                'pgrpcount' => 0, 
                'disp_order' => $displayOrder, 
                'page_id' => 0, 
                'code' => $productCode, 
                'sku' => null, 
                'name' => $this->toUTF8($product['name']),
                'thumbnail' => $product['image'] ? $baseTargetImagePath.$product['image'] : null,
                'image' => $product['image'] ? $baseTargetImagePath.$product['image'] : null,
                'price' => $product['price'],  
                'cost' => 0.0,  
                'descrip' => $this->toUTF8($product['description']),  
                'weight' => $product['weight'], 
                'taxable' => 1, 
                'active' => $product['active'],
            ));


            try{

                if($isNew){
                    $this->mivaQuery->insertProduct($targetProduct);
                } else {
                   $this->mivaQuery->updateProduct($targetProduct);
                }
            } catch(\Exception $e){
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            // image reference for image machine and additional images
            if($product['image']){

                $defaultImage       = $product['image'];
                $largeImage         = preg_replace('/(\..{2,3}$)/', '_LRG$1', $product['image']);
                $swatchLargeImage   = preg_replace('/(\..{2,3}$)/', '_Swatch_LRG$1', $product['image']);
                $swatchImage        = preg_replace('/(\..{2,3}$)/', '_Swatch$1', $product['image']);


                foreach(array(
                    'main' => array(
                        'file' => $largeImage,
                        'local_path' => EXPORTS_DIR.'/images/large/'.$largeImage,
                        'target_path' => $baseTargetImagePath.'large/'.$largeImage,
                        'image_type' => $imageType,
                        'fallback' => array(
                            'file' => $defaultImage,
                            'local_path' => EXPORTS_DIR.'/images/'.$defaultImage,
                            'target_path' => $baseTargetImagePath.$defaultImage,
                        ),
                    ),
                    'swatch' => array(
                        'file' => $swatchLargeImage,
                        'local_path' => EXPORTS_DIR.'/images/large/'.$swatchLargeImage,
                        'target_path' => $baseTargetImagePath.'large/'.$swatchLargeImage,
                        'image_type' => false,
                        'fallback' => array(
                            'file' => $swatchImage,
                            'local_path' => EXPORTS_DIR.'/images/'.$swatchImage,
                            'target_path' => $baseTargetImagePath.$swatchImage,
                        ),
                    )
                ) as $imageName => $imageData) {

                    $file       = $imageData['file'];
                    $localFile  = $this->toUTF8($imageData['local_path']);
                    $targetPath = $this->toUTF8($imageData['target_path']);

                    if (!file_exists($localFile) && $imageData['fallback'] && file_exists($imageData['fallback']['local_path'])) {
                        $file = $imageData['fallback']['file'];
                        $localFile = $imageData['fallback']['local_path'];
                        $targetPath = $imageData['fallback']['target_path'];
                    }

                    if(!file_exists($localFile)){
                        $this->writeLn(sprintf('Could Not Locate Image: %s For %s', $localFile, $imageName));
                        continue;
                    }

                    $targetImage = $this->mivaQuery->getImageByPath($targetPath);

                    if($targetImage){
                        $imageIsNew = false;
                    } else {
                        $imageIsNew = true;
                        $targetImage = array('id' => $this->mivaQuery->nextIdAbstract('Images', 'id'));
                    }

                    $imageInfo = array();
                    if(file_exists($localFile)){
                        $imageInfo = @getimagesize($localFile);
                    }

                    $targetImage = array_merge($targetImage, array(
                        'width'    => isset($imageInfo[0]) ? $imageInfo[0] : 0,
                        'height'   => isset($imageInfo[1]) ? $imageInfo[1] : 0,
                        'refcount' => 0,
                        'image'    => $targetPath,
                    ));


                    try{
                        if($imageIsNew){
                            $this->writeLn(sprintf('Creating Image Record For Image %s', $targetPath));
                            $this->mivaQuery->insertImage($targetImage);
                        } else {
                            $this->writeLn(sprintf('Updating Image Record For Image %s', $targetPath));
                            $this->mivaQuery->updateImage($targetImage);
                        }
                    } catch (\Exception $e) {
                        $this->writeLn(sprintf('Could Not %s Image Record For Product ID %s With Image %s Received Exception %s',
                            $isNew ? 'Create' : 'Update',
                            $product['id'],
                            $targetPath,
                            $e->getMessage()
                        ));
                    }

                    // additional image - MAIN IMAGE
                    if(isset($imageData['image_type']) && $imageData['image_type']){
                        $hasImageType = true;
                        $targetAdditionalImage = $this->mivaQuery->getProductImageByType($product['id'], $imageData['image_type']['id']);
                    } else {
                        $hasImageType = false;
                        $targetAdditionalImage = $this->mivaQuery->getProductImageByImageId($product['id'], $targetImage['id']);
                    }

                    if($targetAdditionalImage){
                        $additionalImageIsNew = false; 
                    } else {
                        $additionalImageIsNew = true;
                        $targetAdditionalImage = array('id' => $this->mivaQuery->nextIdAbstract('ProductImages', 'id'));
                    }

                    $targetAdditionalImage = array_merge($targetAdditionalImage, array( 
                        'product_id'  => $product['id'], 
                        'image_id'    => $targetImage['id'], 
                        'type_id'     => $hasImageType ? $imageData['image_type']['id'] : 0, 
                        'disp_order'  => 0,
                    ));

                    try{
                        if($additionalImageIsNew){
                            $this->writeLn(sprintf('Creating Product Image Record For Product %s And Image %s', $product['id'], $targetImage['id']));
                            $this->mivaQuery->insertProductImage($targetAdditionalImage);
                        } else {
                            $this->writeLn(sprintf('Updating Product Image Record For Product %s And Image %s', $product['id'], $targetImage['id']));
                            $this->mivaQuery->updateProductImage($targetAdditionalImage);
                        }
                    } catch (\Exception $e) {
                        $this->writeLn(sprintf('Could Not %s Product Image Record For Product ID %s With Target Image ID %s Received Exception %s',
                            $additionalImageIsNew ? 'Create' : 'Update',
                            $product['id'],
                            $targetImage['id'],
                            $e->getMessage()
                        ));
                    }                    
                }
            }              

            // category relations
            foreach($productCategories as $k => $productCategory) {
                if($productCategory <= 0){
                    continue;
                }
                $existingProductCategory = $this->mivaQuery->getProductCategory($targetProduct['id'], $productCategory);
                if(!$existingProductCategory){ 
                    $this->mivaQuery->insertProductCategory($targetProduct['id'], $productCategory, $k);
                    $this->writeLn(sprintf('Creating Product Category Relation With ID %s And %s', $targetProduct['id'], $productCategory));
                } else {
                    $this->writeLn(sprintf('Product Category Relation With ID %s And %s Already Exists', $targetProduct['id'], $productCategory));
                }
            }

            // meta data
            $productKeywordsMeta = $this->mivaQuery->getProductMeta($product['id'], $metaKeywordName['id']);

            if(!$productKeywordsMeta){
                $this->mivaQuery->insertProductMeta($product['id'], $metaKeywordName['id'],$this->toUTF8($product['meta_keywords']));
                $this->writeLn(sprintf('Created Product Meta Keywords With ID %s', $product['id']));
            } else {
                $this->mivaQuery->updateProductMeta($product['id'], $metaKeywordName['id'], $this->toUTF8($product['meta_keywords']));
               $this->writeLn(sprintf('Updated Product Meta Keywords With ID %s', $product['id']));
            }

            $productDescriptionMeta = $this->mivaQuery->getProductMeta($product['id'], $metaDescriptionName['id']);
            if(!$productDescriptionMeta){
                $this->mivaQuery->insertProductMeta($product['id'], $metaDescriptionName['id'], $this->toUTF8($product['meta_description']));
                $this->writeLn(sprintf('Created Product Meta Description With ID %s', $product['id']));
            } else {
                $this->mivaQuery->updateProductMeta($product['id'], $metaDescriptionName['id'], $this->toUTF8($product['meta_description']));
                $this->writeLn(sprintf('Updated Product Meta Description With ID %s', $product['id']));
            }
            
            // related products
            $relatedProducts = $this->zenQuery->getRelatedProducts($product['id']);
            foreach($relatedProducts as $relatedProduct) {
                $existingRelatedProduct = $this->mivaQuery->getProductRelatedProduct($product['id'], $relatedProduct['relprod_id']);

                if(!$existingRelatedProduct){  
                    $this->mivaQuery->insertProductRelatedProduct($product['id'], $relatedProduct['relprod_id'], $relatedProduct['disp_order']);
                    $this->writeLn(sprintf('Creating Product Related Product Relation With ID %s And %s', $product['id'], $relatedProduct['relprod_id']));
                } else {
                    $this->writeLn(sprintf('Product Related Product Relation With ID %s And %s Already Exists', $product['id'], $relatedProduct['relprod_id']));
                }

            }            

        }

        $this->writeLn('Operation Completed.');
    }
}