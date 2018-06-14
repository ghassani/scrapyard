<?php

namespace Miva\Migration\Command\ProStores;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Command\ProStores\BaseCommand;
use Miva\Provisioning\Builder\Builder;
use Miva\Provisioning\Builder\Fragment;
use Symfony\Component\Finder\Finder;
use Miva\Migration\Database\ProStores\ProductCsvFileReader;

/**
* ProductDimenstionsToBoxXmlCommand
*
* Generate Provisioning XML to import Box sizes based on ProStores Product Dimensions
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductDimenstionsToBoxXmlCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-product-dimensions-to-boxes-provision-xml')
            ->setDescription('Generate Provisioning XML to import Box sizes based on ProStores Product Dimensions')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_product_additional_images.xml', 'prosores_boxes.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')

        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);  

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
                  return 'prosores_boxes.xml';
                }
                return $fileName;
            },
            false,
            'prosores_boxes.xml'
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

        // build a unique array of box sizes
        $boxSizes = array();
        foreach ($products as $product) {
            if (empty($product['SKU'])) {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['ProductNo']);
            } else {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['SKU']);
            }


            if($product['ContainerCode'] != 'self'){               
            
                $length   = $product['Length'];
                $width    = $product['Width'];
                $height   = $product['Height'];
    
                if ($length > 0 && $width > 0 && $height > 0) {
                    $length = number_format($length, 2, '.', '');
                    $width = number_format($width, 2, '.', '');
                    $height = number_format($height, 2, '.', '');
                    
                    $boxSizes[sprintf('%sx%sx%s', $length, $width, $height)] = array(
                        'length' => $length,
                        'width'  => $width,
                        'height' => $height,
                    );
                }
            }
        }

        foreach ($boxSizes as $asString => $boxSize){
            $boxAdd = new Fragment\BoxAdd();
            $boxAdd->setEnabled(true)
              ->setDescription($asString)
              ->setWidth($boxSize['width'])
              ->setLength($boxSize['length'])
              ->setHeight($boxSize['height']);

             $builder->addFragment($boxAdd);
        }
        
        // now we do product dimensions
        $productBoxSizes = array();
        foreach ($products as $product) {
            if (empty($product['SKU'])) {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['ProductNo']);
            } else {
                $productCode = preg_replace('/[^A-Za-z0-9\_\-]/i', '', $product['SKU']);
            }

            $length   = $product['Length'];
            $width    = $product['Width'];
            $height   = $product['Height'];

            if ($length > 0 && $width > 0 && $height > 0) {
                $length = number_format($length, 2, '.', '');
                $width = number_format($width, 2, '.', '');
                $height = number_format($height, 2, '.', '');
                
                $productShippingRulesUpdate = new Fragment\ProductShippingRulesUpdate();
                
                $productShippingRulesUpdate->setProductCode($productCode)
                  ->setLength($length)
                  ->setWidth($width)
                  ->setHeight($height)
                  ->setShipsInOwnPackaging(false)
                  ->setLimitShippingMethods(false);
                  
                $builder->addFragment($productShippingRulesUpdate);
   
            }
        }
        
        file_put_contents($outFile, $builder->toXml(true));

        if (file_exists($outFile)) {
            $outFile = new \SplFileInfo($outFile);
            $this->writeLn(sprintf('XML Exported To %s', $outFile->getRealPath()));
        } else {
            $this->writeLn('Error Trying To Save Export');
        }

        $this->writeLn('Operation Completed.');

    }
}
