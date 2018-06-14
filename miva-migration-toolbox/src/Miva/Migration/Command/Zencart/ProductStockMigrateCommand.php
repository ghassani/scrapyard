<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ForceUTF8\Encoding AS UTF8Encoder;

/**
 * ProductStockMigrateCommand
 *
 * Migrates Product Data From Zen Cart Database to Miva Merchant Database
 *
 * @author Gassan Idriss <gidriss@mivamerchant.com>
 */
class ProductStockMigrateCommand extends BaseCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('migrate-zencart-inventory')
            ->setDescription('Migrates Product Stock Data from Zen Cart to Miva Merchant')
            ->addOption('zero-stock-inactive', null, InputOption::VALUE_OPTIONAL, 'Consider 0 stock products as no no inventory tracking', false)
            ->addOption('negative-stock-inactive', null, InputOption::VALUE_OPTIONAL, 'Consider negative stock products as no no inventory tracking', false)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $zeroStockInactive = $this->getHelper('dialog')->askConfirmation($output,'<question>Consider 0 stock products as no no inventory tracking? (default: no) </question>',false);
        $negativeStockInactive = $this->getHelper('dialog')->askConfirmation($output,'<question>Consider negative stock products as no no inventory tracking? (default: no) </question>',false);

        $input->setOption('zero-stock-inactive', $zeroStockInactive);
        $input->setOption('negative-stock-inactive', $zeroStockInactive);
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $zeroStockInactive = $input->getOption('zero-stock-inactive');
        $negativeStockInactive = $input->getOption('negative-stock-inactive');

        $products = $this->zenQuery->getProducts();
        $inventorySettings = $this->mivaQuery->getInventorySettings();

        foreach ($products as $product) {
            if (!is_numeric($product['inventory'])) {
                //
                DIE('NON NUMERIC VALUE');
            }

            $markInventoryActive = true;

            if ($zeroStockInactive && $product['inventory'] == 0) {
                $markInventoryActive = false;
            }

            if ($negativeStockInactive && $product['inventory'] < 0) {
                $markInventoryActive = false;
            }


            $productInventorySettings = $this->mivaQuery->getProductInventorySetting($product['id']);
            $productInventoryCounts = $this->mivaQuery->getProductInventoryCount($product['id']);

            $isNewSettings = !$productInventorySettings;
            $isNewCounts = !$productInventoryCounts;

            if ($isNewSettings) {
                $productInventorySettings = array(
                    'product_id' => $product['id'],
                    'active' => $markInventoryActive,
                    'in_short' => '',
                    'in_long' => '',
                    'low_track' => 'd',
                    'low_lvl_d' => '1',
                    'low_level' => '0',
                    'low_short' => '',
                    'low_long' => '',
                    'out_track' => 'd',
                    'out_lvl_d' => '1',
                    'out_level' => '',
                    'out_hide' => 'd',
                    'out_short' => '',
                    'out_long' => '',
                    'ltd_long' => '',
                );

                try {

                    $this->mivaQuery->insertProductInventorySetting($productInventorySettings);

                    $this->writeLn(sprintf('Inserted Product Inventory Settings For Product ID %s With Active Setting %s',
                        $product['id'],
                        $markInventoryActive ? 'true' : 'false'
                    ));

                } catch (\Exception $e) {
                    $this->writeLn(sprintf('Could Not Insert Product Inventory Settings for Product ID %s - Message: %s',
                        $product['id'],
                        $e->getMessage()
                    ));
                }

            } else {
                $productInventorySettings = array_merge($productInventorySettings, array(
                    'active' => $markInventoryActive,
                ));

                try {

                    $this->mivaQuery->updateProductInventorySetting($productInventorySettings);

                    $this->writeLn(sprintf('Updated Product Inventory Settings For Product ID %s With Active Setting %s',
                        $product['id'],
                        $markInventoryActive ? 'true' : 'false'
                    ));
                } catch (\Exception $e) {
                    $this->writeLn(sprintf('Could Not Update Product Inventory Settings for Product ID %s - Message: %s',
                        $product['id'],
                        $e->getMessage()
                    ));
                }
            }

            if ($isNewCounts) {
                $productInventoryCounts = array(
                    'product_id' => $product['id'],
                    'inventory' => $product['inventory']
                );

                try {

                    $this->mivaQuery->insertProductInventoryCount($productInventoryCounts);

                    $this->writeLn(sprintf('Inserted Product Inventory Counts For Product ID %s With Stock Level %s',
                        $product['id'],
                        $product['inventory']
                    ));

                } catch (\Exception $e) {
                    $this->writeLn(sprintf('Could Not Insert Product Inventory Counts for Product ID %s - Message: %s',
                        $product['id'],
                        $e->getMessage()
                    ));
                }

            } else {
                $productInventoryCounts = array_merge($productInventoryCounts, array(
                    'inventory' => $product['inventory'],
                ));

                try {

                    $this->mivaQuery->updateProductInventoryCount($productInventoryCounts);

                    $this->writeLn(sprintf('Updated Product Inventory Counts For Product ID %s With Stock Level %s',
                        $product['id'],
                        $product['inventory']
                    ));

                } catch (\Exception $e) {
                    $this->writeLn(sprintf('Could Not Update Product Inventory Counts for Product ID %s - Message: %s',
                        $product['id'],
                        $e->getMessage()
                    ));
                }
            }

        }
    }

}