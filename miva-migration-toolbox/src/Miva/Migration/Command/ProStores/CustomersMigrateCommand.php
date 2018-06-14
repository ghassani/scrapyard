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
use Miva\Migration\Database\MivaQuery;

/**
* CustomersMigrateCommand
*
* Using a flat file export from Pro Stores for customers, directly insert into a Miva Merchant Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class CustomersMigrateCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-customers-migrate')
            ->setDescription('Using a flat file export from Pro Stores for customers, directly insert into a Miva Merchant Database')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('target-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the target database.', 'miva')                       
            ->addOption('skip-existing', null, InputOption::VALUE_OPTIONAL, 'Skip Existing Customers', false)
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
        
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $targetConnection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection belongs to the Miva Merchant installation? (default: miva)</question>',
            array_combine($connections, $connections),
            'miva'            
        );

        $input->setOption('target-connection', $targetConnection);

        $this->targetDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('target-connection', 'miva'));
        $this->mivaQuery = new MivaQuery($this->targetDB);


        $skipExisting = $this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Skip existing customers?</question>',
            false
        );

        $input->setOption('skip-existing', $skipExisting);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inFile       = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('in-file')));
        $skipExisting = $input->getOption('skip-existing', false);

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

        $this->writeLn(sprintf('Loaded %s Customers from CSV File', ($rowCount-1)));

        $existingLogins = array();

        $k=0;
        
        ksort($customers);

        foreach ($customers as $customer) {
            $customersEmail = !empty($customer['Email']) ? $customer['Email'] : $customer['Email2'];

            if(in_array($customersEmail, $existingLogins)){
                $this->writeLn(sprintf('Customer With Login %s Already Exists. Skipping', $customersEmail));
                $k++;
                continue;
            } 

            $targetCustomer = $this->mivaQuery->getCustomer($customer['CustomerNo']);
            
            if($targetCustomer) {
                if($skipExisting === true) {
                    $this->writeLn(sprintf('%s/%s - Skipping Update on Customer With ID %s', ($k+1), count($customers), $customer['CustomerNo']));
                    $k++;
                    continue;
                }
                $isNew = false;
                $this->writeLn(sprintf('%s/%s - Updating Customer With ID %s', ($k+1), count($customers), $customer['CustomerNo']));
            } else {
                $isNew = true;
                $targetCustomer = array();
                $this->writeLn(sprintf('%s/%s - Creating Customer With ID %s', ($k+1), count($customers), $customer['CustomerNo']));
            }

            $targetCustomer = array_merge($targetCustomer, array(
                'id'         => $customer['CustomerNo'],
                'pgrpcount'  => 0,
                'login'      => $customersEmail,
                'pw_email'   => $customersEmail,
                'password'   => md5(rand(1000000,2000000)),
                'ship_fname' => $customer['FirstName'],
                'ship_lname' => $customer['LastName'],
                'ship_email' => $customersEmail,
                'ship_comp'  => $customer['Company'],
                'ship_phone' => $customer['Phone'],
                'ship_fax'   => $customer['Fax'],
                'ship_addr'  => $customer['Street'],
                'ship_addr2' => $customer['Street2'],
                'ship_city'  => $customer['City'],
                'ship_state' => $customer['State'],
                'ship_zip'   => $customer['Zip'],
                'ship_cntry' => $customer['Country'],
                'bill_fname' => $customer['FirstName'],
                'bill_lname' => $customer['LastName'],
                'bill_email' => $customersEmail,
                'bill_comp'  => $customer['Company'],
                'bill_phone' => $customer['Phone'],
                'bill_fax'   => $customer['Fax'],
                'bill_addr'  => $customer['Street'],
                'bill_addr2' => $customer['Street2'],
                'bill_city'  => $customer['City'], 
                'bill_state' => $customer['State'],
                'bill_zip'   => $customer['Zip'],
                'bill_cntry' => $customer['Country'],
            ));   
            
            array_walk($targetCustomer, array($this, 'toUTF8'));      
        
            try {

                if ($isNew) {
                    $this->mivaQuery->insertCustomer($targetCustomer);
                } else {
                    $this->mivaQuery->updateCustomer($targetCustomer);
                }

            } catch(\Exception $e) {
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            array_push($existingLogins, $customersEmail);
            $k++;
        }

        $this->writeLn('Operation Completed.');
    }

}