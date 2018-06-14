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
* ProductInventoryToXmlCommand
*
* Creates xml to update product inventory levels from prostores product csv export
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductInventoryToXmlCommand extends BaseCommand
{
    
    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-products-inventory-to-provision-xml')
            ->setDescription('Creates xml to update product inventory levels from prostores product csv export')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_products_inventory.xml', 'prostores_products_inventory.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')            
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
                  return 'prostores_products_inventory.xml';
                }
                return $fileName;
            },
            false,
            'prostores_products_inventory.xml'
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
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));

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

        foreach ($products as $product) {
            // Codes may only contain letters, numbers, underscores (_) and dashes (-)
            if (empty($product['SKU'])) {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['ProductNo']);
            } else {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['SKU']);
            }
            
            
            $productInventoryUpdate = new Fragment\InventoryProductSettingsUpdate();
            
        
            /*if ($product['Quantity'] == 0) {
                
                $productInventoryUpdate->setProductCode($productCode)
                  ->setTrackProduct(false);
                  
            } else {*/
                $productInventoryUpdate->setProductCode($productCode)
                  ->setTrackProduct(true)
                  ->setAdjustStockBy($product['Quantity'] ? $product['Quantity'] : '0')
                  ->setTrackLowStockLevel('default')
                  ->setLowStockLevel($product['Threshold'] > 0 ? $product['Threshold'] : 'default')
                  ->setTrackOutOfStockLevel('default')
                  ->setHideOutOfStockProducts('default')
                  ->setOutOfStockLevel('default');
           // }


              $builder->addFragmentToStore($productInventoryUpdate, $storeCode);
              
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