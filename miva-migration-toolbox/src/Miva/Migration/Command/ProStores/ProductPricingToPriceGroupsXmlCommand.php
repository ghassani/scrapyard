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
use Miva\Migration\Database\ProStores\ProductSalesCsvFileReader;
use Miva\Provisioning\Builder\Fragment\Module\CustomFields\ProductFieldAdd;
use Miva\Provisioning\Builder\Fragment\Module\CustomFields\ProductFieldValue;

/**
* ProductPricingToPriceGroupsXmlCommand
*
* Using the product discounts export from prostores, create xml provisioning tags for MM9 discount features
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductPricingToPriceGroupsXmlCommand extends BaseCommand
{
    
    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-product-pricing-to-provision-xml')
            ->setDescription('Using the product discounts export from prostores, create xml provisioning tags for MM9 discount features')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_product_sales.xml', 'prostores_product_sales.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('product-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename that contains the product data.')
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
            '<question>Which file is the products sales export from Pro Stores?</question>',
            $files,
            null
        );

        $input->setOption('in-file', $files[$inFile]);
        
        $inFile = $this->getHelper('dialog')->select(
            $output,
            '<question>Which file is the products export from Pro Stores?</question>',
            $files,
            null
        );

        $input->setOption('product-file', $files[$inFile]);
        
        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the XML to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'prostores_product_sales.xml';
                }
                return $fileName;
            },
            false,
            'prostores_product_sales.xml'
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


    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storeCode   = $input->getOption('store-code');
        $inFile      = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('in-file')));
        $productFile      = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('product-file')));
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));
        
        if (!file_exists($inFile->getRealPath())) {
            $this->writeLn(sprintf('Could Not Find Input File %s', $inFile));
            return static::COMMAND_ERROR;
        }

        if(is_dir($outFile)) {
            $this->writeLn(sprintf('Output File Ended Up Being a Directory %s', $outFile->getRealPath()));
            return static::COMMAND_ERROR;
        }


        try {
            $this->writeLn(sprintf('Reading From Data Source: %s', $inFile->getRealPath()));

            $productSalesReader = new ProductSalesCsvFileReader($inFile->getRealPath());

            $rowCount = $productSalesReader->read();

            $productSales = $productSalesReader::reindexArrayByHeader($productSalesReader->getResult(), 'PromotionConfigNumber');

            $productSalesReader->close();
            
            $this->writeLn(sprintf('Reading From Data Source: %s', $productFile->getRealPath()));

            $productsReader = new ProductCsvFileReader($productFile->getRealPath());

            $rowCount2 = $productsReader->read();

            $products = $productsReader::reindexArrayByHeader($productsReader->getResult(), 'ProductNo');

            $productsReader->close();

            
        } catch (\InvalidArgumentException $e) {
            $this->writeLn($e->getMessage());
            return static::COMMAND_ERROR;
        }


        $this->writeLn(sprintf('Loaded %s Products And %s Pricing Rules from CSV File', ($rowCount2-1), ($rowCount-1)));

        $builder = new Builder($storeCode);
        
        $priceGroups = array();
        
        foreach ($productSales as $productSaleId => $productSale) {
           if (strcasecmp($productSale['ActiveInd'],'Yes') !== 0) {
               continue;
           }           
            
           if (!empty($productSale['Code'])) {
               if ($productSale['DiscountType'] == 'percent') {
                   
                $key = ($productSale['DiscountAmount'] * 100) . '% Off Coupon';
               
               } elseif ($productSale['DiscountType'] == 'currency') {
                   
                   $key = $productSale['DiscountAmount']. ' Off Coupon';
               } elseif ($productSale['DiscountType'] == 'pricediscount' && isset($products[$productSale['ProductNumber']])) {
                   if (empty($products[$productSale['ProductNumber']]['SKU'])) {
                       $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $products[$productSale['ProductNumber']]['ProductNo']);
                   } else {
                      $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $products[$productSale['ProductNumber']]['SKU']);
                   }
                  $key = $productCode.' Sale Price '.$productSale['DiscountAmount'].' Coupon';

               } else {
                   die('ERROR: COUPON DISCOUNT TYPE NOT ACCUONTED FOR: '. $productSale['DiscountType']);
               }
           
           } elseif ($productSale['DiscountType'] == 'percent') {
               $key = ($productSale['DiscountAmount'] * 100) . '% Off';
               
           } elseif ($productSale['DiscountType'] == 'currency') {
               
               $key = $productSale['DiscountAmount']. ' Off';
           } elseif ($productSale['DiscountType'] == 'pricediscount' && isset($products[$productSale['ProductNumber']])) {
                if (empty($products[$productSale['ProductNumber']]['SKU'])) {
                   $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $products[$productSale['ProductNumber']]['ProductNo']);
               } else {
                  $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $products[$productSale['ProductNumber']]['SKU']);
               }
              $key = $productCode.' Sale Price '.$productSale['DiscountAmount'];
           } else {
               die('ERROR: DISCOUNT TYPE NOT ACCUONTED FOR: '. $productSale['DiscountType']);
           }
           
           $key = 'IMPORTED-'.$key;
           
            if (!isset($priceGroups[$key])) {
                $priceGroups[$key] = array(
                    'products'  => array($productSale), 
                    'name'      => $productSale['Name'], 
                    'type'      => $productSale['DiscountType'], 
                    'amount'    => $productSale['DiscountAmount'],
                    'coupon'    => $productSale['Code'],
                    'maxuse'    => $productSale['MaxUse'],
                    'priority'  => 0
                );
                      
                  
            } else {
                $priceGroups[$key]['products'][] = $productSale;
            }
        }

        foreach ($priceGroups as $key => $data) {
            $this->writeLn(sprintf('Price Group: %s - Products: %s', $key, count($data['products']))); 
            
            $firstEntry = current($data['products']);
            
            $priceGroupAdd = new Fragment\PriceGroupAdd();
            
            $priceGroupAdd->setName($key)
              ->setDescription(str_replace('IMPORTED-','', $key));
              
            if (!empty($data['coupon'])) {
                $priceGroupAdd->setEligibility(Fragment\PriceGroupAdd::ELIGIBILITY_COUPON);
            } else {
                $priceGroupAdd->setEligibility(Fragment\PriceGroupAdd::ELIGIBILITY_ALL);
            }
            
            
           if ($data['type'] == 'pricediscount') {
               $priceGroupAdd->setModule(Fragment\PriceGroupAdd::MODULE_DISCOUNT_SALEPRICE);
           } else {
               $priceGroupAdd->setModule(Fragment\PriceGroupAdd::MODULE_DISCOUNT_PRODUCT);
           }


           if ($data['type'] == 'percent') {
               $priceGroupAdd->addSetting('Type', 'Percent')
                 ->addSetting('Discount', $data['amount'] * 100);
                 
           } elseif ($data['type'] == 'currency') {
               $priceGroupAdd->addSetting('Type', 'Fixed')
                 ->addSetting('Discount', str_replace('$', '', $data['amount']));
           }   
           
           $priceGroupAdd->addSetting('Selection', 'Lowest')
             ->addSetting('MaxQuantity', '0')
             ->addSetting('MaxDiscount', '0.00')
             ->setDisplayInBasket(true)
             ->setPriority($data['priority']);


           $builder->addFragment($priceGroupAdd);
           
           
           foreach ($data['products'] as $_product) {
               $product = $products[$_product['ProductNumber']];
               
               if (empty($product['SKU'])) {
                   $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['ProductNo']);
               } else {
                  $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['SKU']);
               }
                           
               $priceGroupProductAssign  = new Fragment\PriceGroupProductAssign();
               
               $priceGroupProductAssign->setGroupName($key)
                 ->setProductCode($productCode);
                 
               $builder->addFragment($priceGroupProductAssign);
               
                if ($data['type'] == 'pricediscount') {
                   $productSalePrice  = new Fragment\Module\Discount\ProductSalePrice();
                   
                   $productSalePrice->setGroupName($key)
                     ->setProductCode($productCode)
                     ->setPrice(str_replace('$', '', $data['amount']));
                     
                   $builder->addFragment($productSalePrice);
               }
                
               if ($data['coupon']) {
                   
                   $addCoupon = new Fragment\CouponAdd();
                   
                   $addCoupon->setCode($data['coupon'])
                     ->setDescription(str_replace('IMPORTED-','', $key))                     
                     ->setEligibility(Fragment\CouponAdd::ELIGIBILITY_ALL)
                     ->setMaxUse($data['maxuse'])
                     ->setActive(true);
                     
                   $addCouponPriceGroup = new Fragment\CouponPriceGroupAssign();
                   
                   $addCouponPriceGroup->setCouponCode($data['coupon'])
                     ->setGroupName($key);
                     
                   $builder->addFragment($addCoupon);
                   $builder->addFragment($addCouponPriceGroup);                     
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