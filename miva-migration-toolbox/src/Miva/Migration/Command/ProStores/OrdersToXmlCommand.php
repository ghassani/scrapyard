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
use Miva\Migration\Database\ProStores\OrderCsvFileReader;

/**
* OrdersToXmlCommand
*
* Using a flat file export from Pro Stores for orders, create an XML Import for Provisioning
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class OrdersToXmlCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-orders-to-provision-xml')
            ->setDescription('Using a flat file export from Pro Stores for orders, create an XML Import for Provisioning')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_orders.xml', 'prostores_orders.xml')
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

        $exportFiles = $finder->files()
          ->depth(1)
          ->in($importDir->getRealPath());

        
        $files = array();

        $k = 1;
        foreach ($exportFiles as $exportFile) {
            $relativePath = str_replace($importDir->getRealPath().DIRECTORY_SEPARATOR, '', $exportFile->getRealPath());
            $files[$k] = $relativePath;
            $k++;
        }

        $inFile = $this->getHelper('dialog')->select(
            $output,
            '<question>Which file is the orders export from Pro Stores?</question>',
            $files,
            null
        );

        $input->setOption('in-file', $files[$inFile]);
        
        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the XML to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'prostores_orders.xml';
                }
                return $fileName;
            },
            false,
            'prostores_orders.xml'
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

        ini_set('memory_limit', '1024M');

        try {

            $ordersReader = new OrderCsvFileReader($inFile->getRealPath());

            $rowCount = $ordersReader->read();

            $orders = $ordersReader::reindexArrayByHeader($ordersReader->getResult(), 'InvoiceNo');

            $ordersReader->close();

            unset($ordersReader);
            $ordersReader = null;
            

        } catch (\InvalidArgumentException $e) {
            $this->writeLn($e->getMessage());
            return static::COMMAND_ERROR;
        }

        $builder = new Builder($storeCode);

        $this->writeLn(sprintf('Loaded %s Orders from CSV File', ($rowCount-1)));
        
        
        foreach ($orders as $order) {
            
            $orderAdd = new Fragment\OrderAdd();
            
            
        }
            
    }

}