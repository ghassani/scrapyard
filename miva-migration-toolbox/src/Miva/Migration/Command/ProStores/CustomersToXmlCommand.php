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
use Miva\Migration\Database\ProStores\CustomerCsvFileReader;

/**
* CustomersToXmlCommand
*
* Using a flat file export from Pro Stores for customers, create an XML Import for Provisioning
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class CustomersToXmlCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-customers-to-provision-xml')
            ->setDescription('Using a flat file export from Pro Stores for customers, create an XML Import for Provisioning')
            ->addOption('store-code', null, InputOption::VALUE_REQUIRED, 'The target Miva Merchant Store Code to Generate XML For', null)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to prostores_customers.xml', 'prostores_customers.xml')
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
            '<question>Which file is the customers export from Pro Stores?</question>',
            $files,
            null
        );

        $input->setOption('in-file', $files[$inFile]);
        
        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the XML to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'prostores_customers.xml';
                }
                return $fileName;
            },
            false,
            'prostores_customers.xml'
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

            $customersReader = new CustomerCsvFileReader($inFile->getRealPath());

            $rowCount = $customersReader->read();

            $customers = $customersReader::reindexArrayByHeader($customersReader->getResult(), 'CustomerNo');

            $customersReader->close();

            unset($customersReader);
            $customersReader = null;
            
        } catch (\InvalidArgumentException $e) {
            $this->writeLn($e->getMessage());
            return statoc::COMMAND_ERROR;
        }

        $builder = new Builder($storeCode);

        $processedLogins = array();

        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        $this->writeLn(sprintf('Loaded %s Customers from CSV File', ($rowCount-1)));

        foreach ($customers as $customerNumber => $customer) {  

            
            $customersEmail = !empty($customer['Email']) ? $customer['Email'] : $customer['Email2'];
            $customerLogin  = substr($customersEmail, 0, stripos($customersEmail, '@')) ;

            if (in_array($customerLogin, $processedLogins)) {
                $this->writeLn(sprintf('Already Processed Customer Login %s - Appending Customer ID', $customerLogin));
                $customerLogin .= $customer['CustomerNo'];
                $processedLogins[] = $customerLogin;
            }

            $processedLogins[] = $customerLogin;           

            $customerAdd = new Fragment\CustomerAdd();
            
            $customerAdd->setLogin($customersEmail)
              ->setLostPasswordEmail($customersEmail)
              ->setPassword(md5(rand(100000,200000)))
              ->setShipFirstName($customer['FirstName'])
              ->setShipLastName($customer['LastName'])
              ->setShipEmail($customersEmail)
              ->setShipPhone($customer['Phone'])
              ->setShipFax($customer['Fax'])
              ->setShipCompany($customer['Company'])
              ->setShipAddress(trim($customer['Street'].' '.$customer['Street2']))
              ->setShipCity($customer['City'])
              ->setShipStateCode($customer['State'])
              ->setShipZip($customer['Zip'])
              ->setShipCountryCode($customer['Country'])
              ->setBillFirstName($customer['FirstName'])
              ->setBillLastName($customer['LastName'])
              ->setBillEmail($customersEmail)
              ->setBillPhone($customer['Phone'])
              ->setBillFax($customer['Fax'])
              ->setBillCompany($customer['Company'])
              ->setBillAddress(trim($customer['Street'].' '.$customer['Street2']))
              ->setBillCity($customer['City'])
              ->setBillStateCode($customer['State'])
              ->setBillZip($customer['Zip'])
              ->setBillCountryCode($customer['Country']);

            $this->writeLn(sprintf('Created CustomerAdd for Customer Email %s And NO. %s With Login %s',
                $customer['Email'], 
                $customer['CustomerNo'], 
                $customerLogin
            ));

            $builder->addFragmentToStore($customerAdd, $storeCode);             
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