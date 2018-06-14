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

/**
* ProductsToXmlCommand
*
* Using a flat file export from Pro Stores for products, create an XML Import for Provisioning
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductsToXmlCommand extends BaseCommand
{
    private static $defaultCustomFieldMap = array(
        'ProductNo'      => null,
        'Product'        => null,
        'SKU'            => null,
        'Oem'            => 'oem',
        'Supplier'       => 'supplier',
        'ExtText1'       => 'exttext1',
        'ExtText2'       => 'exttext2',
        'ExtText3'       => 'exttext3',
        'ExtText4'       => 'exttext4',
        'ExtText5'       => 'exttext5',
        'ExtText6'       => 'exttext6',
        'Isbn'           => 'isbn',
        'Brand'          => 'brand',
        'Mpn'            => 'mpn',
        'Upc'            => 'upc',
        'Ean'            => 'ean',
        'Condition'      => 'condition',
        'Category'       => 'meta_keywods',
        'Keywords'       => 'meta_description',
        'GoogleCategory' => 'google_category',
        'GoogleAgeGroup' => 'google_age_group',
        'GoogleGender'   => 'google_gender',
        'GoogleColor'    => 'google_color',
        'GoogleSize'                => 'google_size',
        'NaturalSearchKeywords'     => 'natural_meta_keywods',
        'NaturalSearchDescription'  => 'natural_meta_description',
        'Shipping'                  => 'shipping',
        'ShippingExemptInd'         => 'shipping_exempt_ind',
        'SaleExclude'               => 'sale_exclude',
        'Brief'                     => 'brief',
        'Template'                  => '',
        'Featured'                  => ''
        
    );

    private $customFieldMap;


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-products-to-provision-xml')
            ->setDescription('Using a flat file export from Pro Stores for products, create an XML Import for Provisioning')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_orders.xml', 'prostores_products.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'Domain Name. Used for downloading images which are not available locally')
            ->addOption('additional-images', null, InputOption::VALUE_REQUIRED, 'Use Additional Images for Product Images. If not, only legacy will be used.')
            ->addOption('base-target-image-path', null, InputOption::VALUE_OPTIONAL, 'Path Prefix for Images to Import into Full Sized Image and Additional Images.', 'graphics/00000001/')
        ;

        foreach (static::$defaultCustomFieldMap as $csvHeader => $customFieldCode) {
            $this->addOption('field-'.$csvHeader, null, InputOption::VALUE_OPTIONAL, sprintf('Custom Field Map For %s', $csvHeader), $customFieldCode);
        }

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
                  return 'prostores_products.xml';
                }
                return $fileName;
            },
            false,
            'prostores_products.xml'
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


        $domain = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What is the base url to the root of the pro stores domain?</question>',
            function ($domain) {
                if (empty($domain)) {
                    throw new \InvalidArgumentException('Must supply domain');
                }
                return $domain;
            },
            false
        );

        $input->setOption('domain', $domain); 
        
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


        $useCustomField = $this->getHelper('dialog')->askConfirmation($output,'<question>Would you like to specify custom fields for each column which can\'t be identified as a standard field? (default: no)</question>', false);

        if($useCustomField) {
            $this->customFieldMap = array();

            foreach (static::$defaultCustomFieldMap as $headerName => $customFieldCode) {
                $response = $this->getHelper('dialog')->askAndValidate(
                    $output,
                    sprintf('<question>What custom field code to use for %s (default: %s) ?</question>', $headerName, empty($customFieldCode) ? '[SKIPPED]' : $customFieldCode),
                    function ($field) {
                        return $field;
                    },
                    false,
                    $customFieldCode
                ); 

                $this->customFieldMap[$headerName] = $customFieldCode;
            }

        } else {
            $this->customFieldMap = static::$defaultCustomFieldMap;
        }
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
        $domain      = $input->getOption('domain'); 

        if (!isset($this->customFieldMap)) {
            $this->customFieldMap = array();
            foreach(static::$defaultCustomFieldMap as $header => $field){
               $this->customFieldMap[$header] = $input->getOption('field-'.$header);
            }      
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

        // create custom fields
        foreach ($this->customFieldMap as $headerName => $fieldName) {

            if (empty($fieldName)) {
                continue;
            }
            
            $productFieldAdd = new Fragment\Module\CustomFields\ProductFieldAdd();
            $productFieldAdd->setCode($fieldName)
              ->setFieldType('textfield')
              ->setName($fieldName);

            $builder->addFragmentToStore($productFieldAdd, $storeCode);
        }


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

            foreach ($this->customFieldMap as $csvHeaderName => $customFieldCode) {
                if (empty($customFieldCode)) {
                    continue;
                }
                if (isset($product[$csvHeaderName]) && !empty($product[$csvHeaderName])) {                   
                    $productFieldValue = new ProductFieldValue();
                    $productFieldValue->setProductCode($productCode)
                      ->setFieldCode($customFieldCode)
                      ->setValue($product[$csvHeaderName]);
                      
                    $builder->addFragmentToStore($productFieldValue, $storeCode);
                }
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