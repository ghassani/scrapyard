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
use Miva\Migration\Database\ProStores\AffiliateCsvFileReader;
use Miva\Migration\Database\MivaQuery;

/**
* AffiliatesMigrateCommand
*
* Using a flat file export from Pro Stores for affiliates, directly insert into a Miva Merchant Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class AffiliatesMigrateCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-affiliate-migrate')
            ->setDescription('Using a flat file export from Pro Stores for affiliates, directly insert into a Miva Merchant Database')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ->addOption('target-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the target database.', 'miva')                       
            ->addOption('skip-existing', null, InputOption::VALUE_OPTIONAL, 'Skip Existing Affiliates', false)
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
            '<question>Which file is the affiliates export from Pro Stores?</question>',
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
            '<question>Skip existing affiliates?</question>',
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

            $affiliatesReader = new AffiliateCsvFileReader($inFile->getRealPath());

            $rowCount = $affiliatesReader->read();

            $affiliates = $affiliatesReader::reindexArrayByHeader($affiliatesReader->getResult(), 'AffiliateNo');

            $affiliatesReader->close();

            unset($affiliatesReader);
            $affiliatesReader = null;
            
        } catch (\InvalidArgumentException $e) {
            $this->writeLn($e->getMessage());
            return statoc::COMMAND_ERROR;
        }

        $this->writeLn(sprintf('Loaded %s Affiliates from CSV File', ($rowCount-1)));

        $k=0;

        $defaultPayset = $this->mivaQuery->getVikingCoderDefaultPayset();

        ksort($affiliates);

        foreach ($affiliates as $affiliate) {
            
            $targetAffiliate = $this->mivaQuery->getVikingCoderAffiliateById($affiliate['AffiliateNo']);
            
            if($targetAffiliate) {
                if($skipExisting === true) {
                    $this->writeLn(sprintf('%s/%s - Skipping Update on Affiliate With ID %s', ($k+1), count($affiliate), $affiliate['AffiliateNo']));
                    $k++;
                    continue;
                }
                $isNew = false;
                $this->writeLn(sprintf('%s/%s - Updating Affiliate With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            } else {
                $isNew = true;
                $targetAffiliate = array();
                $this->writeLn(sprintf('%s/%s - Creating Affiliate With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            }

            // try to load the customer for association with their account
            $customer = $this->mivaQuery->getCustomerByLogin($affiliate["Email"]);


            if (empty($affiliate['Contact'])) {
                if ($customer) {
                    $affiliateFirstName = $customer['ship_fname'];
                    $affiliateLastName  = $customer['ship_lname'];
                } else {
                    $affiliateFirstName = null;
                    $affiliateLastName  = null;
                }
            } else {
                $affiliateName = explode(' ', str_replace('"', '', $affiliate['Contact']));

                if (count($affiliateName) == 1) {
                    $affiliateFirstName = implode('', $affiliateName);
                    $affiliateLastName  = null;

                } else if(count($affiliateName) == 2) {
                    $affiliateFirstName = $affiliateName[0];
                    $affiliateLastName  = $affiliateName[1];
                } else {
                    $affiliateFirstName = $affiliateName[0];
                    unset($affiliateName[0]);
                    $affiliateLastName  = implode(' ', $affiliateName);
                }
            }
            
            $targetAffiliate = array_merge($targetAffiliate, array(
                'affiliate'  => $affiliate['AffiliateNo'],
                'referredby' => 0,
                'notify'     => 1,
                'html'       => 1,
                'code'       => $affiliate['AffiliateNo'],
                'cust_id'    => $customer ? $customer['id'] : 0,
                'email'      => $affiliate['Email'],
                'fname'      => $affiliateFirstName,
                'lname'      => $affiliateLastName,
                'company'    => $affiliate['Affiliate'],
                'phone1'     => $affiliate['Phone'],
                'phone2'     => null,
                'fax'        => $affiliate['Fax'],
                'address'    => preg_replace('/\s{2,}/', ' ', $affiliate['Street'].' '.$affiliate['Street2']),
                'city'       => $affiliate['City'],
                'state'      => $affiliate['State'],
                'zip'        => $affiliate['Zip'],
                'country'    => $affiliate['Country'],
                'url'        => $affiliate['URL'],
                'start'      => time(),
                'po_last'    => 0,
                'po_lstpd'   => 0,
                'po_paid'    => 0.00,
                'email_bod'  => null,
                'subject'    => null,
                'invoice'    => null,
                'ordertemp'  => null,
                'settings'   => 0,
                'note'       => 'Imported From Pro Stores',
                'active'     => 1,
                'def_ctgyt'  => 0,
                'def_prodt'  => 0,
                'redirect'   => null,
                'mintotal'   => 0,
                'inc_tax'    => 0,
                'issuecert'  => 0,
                'certtemp'   => 0,
                'certnote'   => null,
                'def_payout' => 1,
                'def_temps'  => 0,
                'def_certs'  => 0,
                'def_disc'   => 1,
                'head'       => null,
                'body'       => null,
                'discount'   => 0.00,
                'disc_prcnt' => 1,
                'disc_prod'  => 0,
                'disc_name'  => 'Referral Discount',
            ));   
            
            array_walk($targetAffiliate, array($this, 'toUTF8'));      
        
            try {

                if ($isNew) {
                    $this->mivaQuery->insertVikingCoderAffiliate($targetAffiliate);
                } else {
                    $this->mivaQuery->updateVikingCoderAffiliate($targetAffiliate);
                }

            } catch(\Exception $e) {
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            // process affiliate2 table, holds some setting related stuff for the affiliate
            // we set unknown data from prostores with the module defaults
            $targetAffiliate2 = $this->mivaQuery->getVikingCoderAffiliate2ById($affiliate['AffiliateNo']);
            
            if($targetAffiliate2) {
                $isNewAffiliate2 = false;
                $this->writeLn(sprintf('%s/%s - Updating Affiliate2 With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            } else {
                $isNewAffiliate2 = true;
                $targetAffiliate2 = array();
                $this->writeLn(sprintf('%s/%s - Creating Affiliate2 With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            }

            $targetAffiliate2 = array_merge($targetAffiliate2, array(
                'affiliate'  => $affiliate['AffiliateNo'],
                'field1'     => '',
                'field2'     => '',
                'field3'     => '',
                'field4'     => '',
                'products'   => '',
                'rest_type'  => 0,
                'ret_over'   => '',
                'def_retain' => 1,
                'use_pgrp'   => '',
                'retention'  => '',
                'orders'     => '',
                'linkpass'   => '',
                'used_bonus' => 0,
                'aff_store'  => null,
                'aff_zips'   => null,
                'eml_prod'   => '',
                'eml_attr'   => '',
            ));

            try {

                if ($isNewAffiliate2) {
                    $this->mivaQuery->insertVikingCoderAffiliate2($targetAffiliate2);
                } else {
                    $this->mivaQuery->updateVikingCoderAffiliate2($targetAffiliate2);
                }

            } catch(\Exception $e) {
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            //now do the affiliate paysets
            // we use the default payset as the default values
            $targetAffiliatePayset = $this->mivaQuery->getVikingCoderAffiliatePayset($affiliate['AffiliateNo']);
            
            if($targetAffiliatePayset) {
                $isNewAffiliatePayset = false;
                $this->writeLn(sprintf('%s/%s - Updating Affiliate Payset With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            } else {
                $isNewAffiliatePayset = true;
                $targetAffiliatePayset = array();
                $this->writeLn(sprintf('%s/%s - Creating Affiliate Payset With ID %s', ($k+1), count($affiliates), $affiliate['AffiliateNo']));
            }

            $targetAffiliatePayset = array_merge($targetAffiliatePayset, array(
                'affiliate' => $affiliate['AffiliateNo'],
                'po_pernum' => $defaultPayset['po_pernum'],
                'po_pertyp' => $defaultPayset['po_pertyp'],
                'po_basis'  => $defaultPayset['po_basis'],
                'po_flat'   => $defaultPayset['po_flat'],
                'po_prcnt'  => $defaultPayset['po_prcnt'],
                'po_hit'    => $defaultPayset['po_hit'],
                'po_prod'   => $defaultPayset['po_prod'],
            ));

            try {

                if ($isNewAffiliatePayset) {
                    $this->mivaQuery->insertVikingCoderAffiliatePayset($targetAffiliatePayset);
                } else {
                    $this->mivaQuery->updateVikingCoderAffiliatePayset($targetAffiliatePayset);
                }

            } catch(\Exception $e) {
                $this->writeLn($e->getMessage());
                return static::COMMAND_ERROR;
            }

            $k++;
        }

        $this->writeLn('Operation Completed.');
    }

}