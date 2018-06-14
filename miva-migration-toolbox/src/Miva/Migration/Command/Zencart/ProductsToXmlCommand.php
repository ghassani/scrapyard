<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Miva\Provisioning\Builder\Builder;
use Miva\Provisioning\Builder\Fragment;

/**
* ProductsToXmlCommand
*
* Using configured database settings, read all product data and export it to Miva Merchant XML Provisioning
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ProductsToXmlCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('zencart-products-to-provision-xml')
            ->setDescription('Using configured database settings, read all product data and export it to Miva Merchant XML Provisioning')
            ->addArgument('store_code', InputArgument::REQUIRED, 'The target Miva Merchant Store Code to Generate XML For')
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'Optionally specify a filename to export as. Defaults to products.xml', 'products.xml')
            ->addOption('store_number', null, InputOption::VALUE_OPTIONAL, 'Store Number For Graphics/CSS Directory Paths', '00000001')
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // start a builder instance
        $storeCode = $input->getArgument('store_code');
        $storeNumber = $input->getOption('store_number');

        $builder = new Builder($storeCode);

        $productsQuery = $this->zenDb->prepare('
            SELECT * FROM products p 
                LEFT JOIN products_description d ON p.products_id = d.products_id AND d.language_id = 1 
                LEFT JOIN meta_tags_products_description m ON p.products_id = m.products_id AND m.language_id = 1                 
            LIMIT 100
        ');
        $productsQuery->execute();
        $products = $productsQuery->fetchAll();

        $establishedCodes = array();

        foreach($products as $product) {
            $productCode = $product['products_model'];

            if(empty($productCode)){
                $productCode = $product['products_id'];
                if(in_array($productCode, $establishedCodes)){
                    $output->writeLn(sprintf('<error>Code %s Already Exists!</error>', $product['products_id']));
                    return static::COMMAND_ERROR;
                }
            } else if(in_array($productCode, $establishedCodes)){
                $productCode .= $product['products_id'];
                if(in_array($productCode, $establishedCodes)){
                    $output->writeLn(sprintf('<error>Code %s Already Exists!</error>', $product['products_model']));
                    return static::COMMAND_ERROR;
                }
            }

            $productAdd = new Fragment\ProductAdd();

            $productAdd->setCode($productCode)
              ->setName(htmlspecialchars(sanitize($product['products_name'])))
              ->setPrice($product['products_price'])
              ->setWeight($product['products_weight'])              
              ->setTaxable(true)
              ->setActive($product['products_status'] == 1 ? true : false);

            if(!empty($product['products_description'])){
                $productAdd->setDescription(htmlspecialchars(sanitize($product['products_description'])));
            }

            if(!empty($product['products_image'])){
                $productAdd->setFullSizeImage(sprintf('graphics/%s/%s', $storeNumber, $product['products_image']));
            }
            

            $builder->addFragmentToStore($productAdd, $storeCode);

            $output->writeLn($product['products_model']);

            array_push($establishedCodes, $product['products_model']);
        }


        $targetFile = EXPORTS_DIR.'/'.$input->getOption('filename');

        file_put_contents($targetFile, $builder->toXml());

        $output->writeLn('Operation Completed. File Ouputted To '. $targetFile);
    }
}