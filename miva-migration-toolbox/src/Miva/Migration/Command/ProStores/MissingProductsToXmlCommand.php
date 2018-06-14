<?php

namespace Miva\Migration\Command\ProStores;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Provisioning\Builder\Builder;
use Miva\Provisioning\Builder\Fragment;
use Symfony\Component\Finder\Finder;
use Miva\Migration\Database\ProStores\ProductCsvFileReader;
use Miva\Provisioning\Builder\Fragment\Module\CustomFields\ProductFieldAdd;
use Miva\Provisioning\Builder\Fragment\Module\CustomFields\ProductFieldValue;
use Miva\Migration\Database\MivaQuery;

/**
* MissingProductsToXmlCommand
*
* 
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class MissingProductsToXmlCommand extends BaseCommand
{
    

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-missing-products-to-provision-xml')
            ->setDescription('')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_products_missing.xml', 'prostores_products_missing.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('additional-images', null, InputOption::VALUE_REQUIRED, 'Use Additional Images for Product Images. If not, only legacy will be used.')
            ->addOption('base-target-image-path', null, InputOption::VALUE_OPTIONAL, 'Path Prefix for Images to Import into Full Sized Image and Additional Images.', 'graphics/00000001/')
            ->addOption('source-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the source database.', 'miva');

        ;


    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // get all export directory files
        $finder = new Finder();

        $importDir = new \SplFileInfo(IMPORTS_DIR);
        $exportDir = new \SplFileInfo(EXPORTS_DIR);

        $importFiles = $finder->files()
          ->depth(1)
          ->in($importDir->getRealPath());

        
        $files = array();

        $k = 1;
        foreach ($importFiles as $importFile) {
            $relativePath = str_replace($importDir->getRealPath().DIRECTORY_SEPARATOR, '', $importFile->getRealPath());
            $files[$k] = $relativePath;
            $k++;
        }

        $inFile = $this->getHelper('dialog')->select(
            $output,
            '<question>Which file is the products export from Pro Stores?</question>',
            $files,
            null
        );

        $input->setOption('in-file', $files[$inFile]);
        
        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the XML to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'prostores_products_missing.xml';
                }
                return $fileName;
            },
            false,
            'prostores_products_missing.xml'
        );

        $input->setOption('out-file', $outFile);

        $storeCode = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the Miva Merchant Store Code?:</question>',
            function ($storeCode) {
                return $storeCode;
            },
            false,
            ''
        );
        $input->setOption('store-code', $storeCode);

       
        $additionalImages = $this->getHelper('dialog')->askConfirmation($output,'<question>Use Additional Images for Product Images? (default: yes; if not, only legacy will be used)</question>', true);

        $input->setOption('additional-images', $additionalImages);

        $baseTargetImagePath = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the base path (from mm root) to the product images folder? (default: graphics/00000001/) </question>',
            function ($path) {
                return $path;
            },
            false,
            'graphics/00000001/'
        );

        $input->setOption('base-target-image-path', $baseTargetImagePath); 
        
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $sourceConnection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection belongs to the Source Miva Merchant installation? (default: miva)</question>',
            array_combine($connections, $connections),
            'miva'            
        );

        $input->setOption('source-connection', $sourceConnection);

        $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection', 'miva'));
        $this->mivaQuery = new MivaQuery($this->sourceDB);
    
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storeCode   = $input->getOption('store-code');
        $inFile      = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('in-file')));
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));
        $doAdditionalImages   = $input->getOption('additional-images');
        $baseImagePath      = $input->getOption('base-target-image-path'); 
    
        if (!isset($this->sourceDB)) {
            $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection'));
            $this->mivaQuery = new MivaQuery($this->sourceDB);
        }
        
        if (!file_exists($inFile->getRealPath())) {
            $this->writeLn(sprintf('Could Not Find Input File %s', $inFile));
            return static::COMMAND_ERROR;
        }

        if(is_dir($outFile)) {
            $this->writeLn(sprintf('Output File Ended Up Being a Directory %s', $outFile->getRealPath()));
            return static::COMMAND_ERROR;
        }

        $this->writeLn(sprintf('Reading From Data Source: %s', $inFile->getRealPath()));

        try {

            $productsReader = new ProductCsvFileReader($inFile->getRealPath());

            $rowCount = $productsReader->read();

            $products = $productsReader::reindexArrayByHeader($productsReader->getResult(), 'ProductNo');

            $productsReader->close();

            unset($productsReader);
            $productsReader = null;
            

        } catch (\InvalidArgumentException $e) {
            $this->writeLn($e->getMessage());
            return static::COMMAND_ERROR;
        }

        $builder = new Builder($storeCode);

        $this->writeLn(sprintf('Loaded %s Products from CSV File', ($rowCount-1)));

        
        // add main product image type
        $mainImageType = new Fragment\ImageTypeAdd();

        $mainImageType->setCode('main')
          ->setDescription('The Product Main Image');

        $builder->addFragmentToStore($mainImageType, $storeCode);
          
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


        foreach ($products as $product) {
            // Codes may only contain letters, numbers, underscores (_) and dashes (-)
            if (empty($product['SKU'])) {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['ProductNo']);
            } else {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['SKU']);
            }
            
            $existingProduct = $this->mivaQuery->getProductByCode($productCode);
            
            if ($existingProduct) {
                continue;
            }
            

            $productAdd = new Fragment\ProductAdd();

            $productAdd->setCode($productCode)
              ->setName($product['Product'])
              ->setDescription($product['Description'])
              ->setSku($product['SKU'])
              ->setPrice(number_format(str_replace('$', '', $product['Price']),2,'.',''))
              ->setCost(number_format(str_replace('$', '', $product['Cost']),2,'.',''))
              ->setActive(strtolower($product['Active']) == 'yes' ? true : false)
              ->setTaxable(strtolower($product['Taxable']) == 'yes' ? true : false);
              
            if ($product['Weight']) {
                $productAdd->setWeight(number_format($product['Weight'], 2));
            }
              
              
            // locate images on the local filesystem in the EXPORT_DIR /images folder
           if (!file_exists(EXPORTS_DIR.'/images/'.$product['Thumbnail'])) {
               // try to download it
               $thumbnailUrl = $domain.'/catalog/'.rawurlencode($product['Thumbnail']);
               
               $thumbnail = file_get_contents($thumbnailUrl);
               
               $thumbnailDir = dirname(EXPORTS_DIR.'/images/'.$product['Thumbnail']);
               
               if (!is_dir($thumbnailDir)){
                   $this->writeLn(sprintf('Created Directory %s', $thumbnailDir));
                   mkdir($thumbnailDir, 0777, true);
               }
               
               if ($thumbnail !== false) {
                   $this->writeLn(sprintf('Downloaded Thumbnail Image for %s [%s]', $productCode, $thumbnailUrl));
                     
                   file_put_contents(EXPORTS_DIR.'/images/'.$product['Thumbnail'], $thumbnail);
               } else {
                   $this->writeLn(sprintf('Could Not Download Image - For %s [%s] - Not Found or Other Error', $productCode, $thumbnailUrl));
               }
           }

           if (!file_exists(EXPORTS_DIR.'/images/'.$product['Photo'])) {
               // try to download it
               $imageUrl = $domain.'/catalog/'.rawurlencode($product['Photo']);
               
               $image = file_get_contents($imageUrl);
               
               $imageDir = dirname(EXPORTS_DIR.'/images/'.$product['Photo']);
               
               if (!is_dir($imageDir)){                   
                   if(mkdir($imageDir, 0777, true)) {
                       $this->writeLn(sprintf('Created Directory %s', $imageDir));
                   } else {
                       $this->writeLn(sprintf('Could Not Create Created Directory %s', $imageDir));
                   }                   
               }
               
               if ($image !== false) {
                   $this->writeLn(sprintf('Downloaded Image for %s [%s]', $productCode, $imageUrl));
                     
                   file_put_contents(EXPORTS_DIR.'/images/'.$product['Photo'], $image);
               } else {
                   $this->writeLn(sprintf('Could Not Download Image - For %s [%s] - Not Found or Other Error', $productCode, $imageUrl));
               }
           }



            if (!empty($product['Thumbnail'])) {
                $productAdd->setThumbnailImage($baseImagePath.'/'.$product['Thumbnail']);
            }


            if (!empty($product['Photo'])) {
                $productAdd->setFullSizeImage($baseImagePath.'/'.$product['Photo']);
            }

            $builder->addFragmentToStore($productAdd, $storeCode);

            if ($doAdditionalImages && !empty($product['Photo'])) {
                $imageAdd = new Fragment\ImageAdd();
                $imageAdd->setFilePath($baseImagePath.'/'.$product['Photo']);

                $productImageAdd = new Fragment\ProductImageAdd();

                $productImageAdd->setProductCode($productCode)
                  ->setFilePath($baseImagePath.'/'.$product['Photo'])
                  ->setTypeCode('main');

                $builder->addFragmentToStore($imageAdd, $storeCode);
                $builder->addFragmentToStore($productImageAdd, $storeCode);
            }

        }

        file_put_contents($outFile, $builder->toXml(true));

        if (file_exists($outFile)) {
            $outFile = new \SplFileInfo($outFile);
            $this->writeLn(sprintf('XML Exported To %s', $outFile->getRealPath()));
        } else {
            $this->writeLn('Error Trying To Save Export');
        }   
    }
}