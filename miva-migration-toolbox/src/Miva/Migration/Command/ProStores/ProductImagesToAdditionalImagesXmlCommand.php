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
* ProductImagesToAdditionalImagesXmlCommand
*
* Generates XML Provisioning for Product Images as Additional Images
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductImagesToAdditionalImagesXmlCommand extends BaseCommand
{

    const CUSTOM_FIELD_CODE = 'imported_product_name';

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-product-images-to-additional-images-provision-xml')
            ->setDescription('Generates XML Provisioning for Product Images as Additional Images')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_product_additional_images.xml', 'prostores_product_additional_images.xml')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('type-code', null, InputOption::VALUE_REQUIRED, 'Image Type Code')
            ->addOption('base-target-image-path', null, InputOption::VALUE_OPTIONAL, 'Path Prefix for Images to Import into Full Sized Image and Additional Images.', 'graphics/00000001/')

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
                  return 'prostores_product_additional_images.xml';
                }
                return $fileName;
            },
            false,
            'prostores_product_additional_images.xml'
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


        $typeCode = $this->getHelper('dialog')->askAndValidate(
            $output,
            '<question>What (if any) image type to assign the image to?:</question>',
            function ($typeCode) {
                return $typeCode;
            },
            false,
            ''
        );

        $input->setOption('type-code', $typeCode);


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
    }


    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storeCode   = $input->getOption('store-code');
        $typeCode    = $input->getOption('type-code');
        $inFile      = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('in-file')));
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));
		$baseImagePath = $input->getOption('base-target-image-path'); 

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
            $productCode = str_replace(array(' ', '.', '/', '\\', '(', ')'), '', $product['SKU']);

            $imageAdd = new Fragment\ImageAdd();

            $imageAdd->setFilePath($baseImagePath.$product['Photo']);

            $builder->addFragmentToStore($imageAdd, $storeCode);

            $productImageAdd = new Fragment\ProductImageAdd();

            $productImageAdd->setProductCode($productCode)
              ->setFilePath($baseImagePath.$product['Photo']);

            if($typeCode) {
             	$productImageAdd->setTypeCode($typeCode);
            }
                     

            $builder->addFragmentToStore($productImageAdd, $storeCode);
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