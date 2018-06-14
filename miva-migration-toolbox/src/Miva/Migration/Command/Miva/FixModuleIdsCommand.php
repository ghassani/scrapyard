<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Database\MivaQuery;

/**
* FixModuleIdsCommand
*
* Fix module ID relations in Orders and other tables when migrating data
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class FixModuleIdsCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('fix-miva-module-ids')
            ->setDescription('Fix module ID relations in Orders and other tables when migrating data')
            ->addOption('source-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the source database.', 'miva');

        ;
    }


    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $targetConnection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection belongs to the Source Miva Merchant installation? (default: miva)</question>',
            array_combine($connections, $connections),
            'miva'            
        );

        $input->setOption('source-connection', $targetConnection);

        $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection', 'miva'));
        $this->sourceMivaQuery = new MivaQuery($this->sourceDB);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (!isset($this->sourceMivaQuery)) {
            $this->sourceDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('source-connection', 'miva'));
            $this->sourceMivaQuery = new MivaQuery($this->sourceDB);
        }
        
        if (!isset($this->mivaQuery)) {
            $this->targetDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('target-connection', 'miva'));
            $this->mivaQuery = new MivaQuery($this->targetDB);
        }
        
        $sourceModules = $this->sourceMivaQuery->getModules();
        $targetModules = $this->mivaQuery->getModules();
        
        
        //Orders
        $sourceOrderModuleIds = $this->sourceMivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT o.ship_id, m.code')
          ->from($this->sourceMivaQuery->tablePrefix.'Orders', 'o')
          ->leftJoin('o', 'Modules', 'm', 'm.id = o.ship_id')
          ->where('o.ship_id > 0')
          ->execute()
          ->fetchAll();
          
        $targetOrderModuleIds = $this->mivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT o.ship_id, m.code')
          ->from($this->mivaQuery->tablePrefix.'Orders', 'o')
          ->leftJoin('o', 'Modules', 'm', 'm.id = o.ship_id')
          ->where('o.ship_id > 0')
          ->execute()
          ->fetchAll();
          
        foreach ($sourceOrderModuleIds as $sourceOrderModule) {
            
            $hasMatch = false;
            foreach ($targetModules  as $targetModule) {
                if($targetModule['code'] == $sourceOrderModule['code']) {
                    $hasMatch = true;
                    $this->writeLn(sprintf('Order - Found Matching Module For: %s - %s To %s - %s', 
                        $sourceOrderModule['ship_id'], 
                        $sourceOrderModule['code'],
                        $targetModule['id'], 
                        $targetModule['code']
                    ));
                    
                    $updateQueryResult = $this->mivaQuery->getConnection()
                        ->createQueryBuilder()
                        ->update($this->mivaQuery->tablePrefix.'Orders', 'o')
                        ->set('o.ship_id', '?')
                        ->where('o.ship_id = ?')
                        ->setParameter(0, $targetModule['id'])
                        ->setParameter(1, $sourceOrderModule['ship_id'])
                        ->execute();
                        
                    $this->writeLn(sprintf('Orders - Updated %s Orders', $updateQueryResult));
                }
            }
            
            if (!$hasMatch) {
                $this->writeLn(sprintf('OrderCharges - Target Has No Matching Module For: %s - %s', 
                    $sourceOrderModule['ship_id'], 
                    $sourceOrderModule['code']
                ));
            }
        }
        
     
        // OrderCharges
        $sourceOrderChargesModuleIds = $this->sourceMivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT o.module_id, m.code')
          ->from($this->sourceMivaQuery->tablePrefix.'OrderCharges', 'o')
          ->leftJoin('o', 'Modules', 'm', 'm.id = o.module_id')
          ->where('module_id > 0')
          ->execute()
          ->fetchAll();
          
        $targetOrderChargesModuleIds = $this->mivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT o.module_id, m.code')
          ->from($this->mivaQuery->tablePrefix.'OrderCharges', 'o')
          ->leftJoin('o', 'Modules', 'm', 'm.id = o.module_id')
          ->where('module_id > 0')
          ->execute()
          ->fetchAll();
          
        foreach ($sourceOrderChargesModuleIds as $sourceOrderChargesModule) {
            
            $hasMatch = false;
            foreach ($targetModules  as $targetModule) {
                if($targetModule['code'] == $sourceOrderChargesModule['code']) {
                    $hasMatch = true;
                    $this->writeLn(sprintf('OrderCharges - Found Matching Module For: %s - %s To %s - %s', 
                        $sourceOrderChargesModule['module_id'], 
                        $sourceOrderChargesModule['code'],
                        $targetModule['id'], 
                        $targetModule['code']
                    ));
                    
                    $updateQueryResult = $this->mivaQuery->getConnection()
                        ->createQueryBuilder()
                        ->update($this->mivaQuery->tablePrefix.'OrderCharges', 'o')
                        ->set('o.module_id', '?')
                        ->where('o.module_id = ?')
                        ->setParameter(0, $targetModule['id'])
                        ->setParameter(1, $sourceOrderChargesModule['module_id'])
                        ->execute();
                        
                    $this->writeLn(sprintf('OrderCharges - Updated %s Order Charges', $updateQueryResult));
                }
            }
            
            if (!$hasMatch) {
                $this->writeLn(sprintf('OrderCharges - Target Has No Matching Module For: %s - %s', 
                    $sourceOrderChargesModule['module_id'], 
                    $sourceOrderChargesModule['code']
                ));
            }
        }
        
        
        // OrderPayments
        $sourceOrderpaymentModuleIds = $this->sourceMivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT p.pay_id, m.code')
          ->from($this->sourceMivaQuery->tablePrefix.'OrderPayments', 'p')
          ->leftJoin('p', 'Modules', 'm', 'm.id = p.pay_id')
          ->where('p.pay_id > 0')
          ->execute()
          ->fetchAll();
          
        $targetOrderPaymentModuleIds = $this->mivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('DISTINCT p.pay_id, m.code')
          ->from($this->mivaQuery->tablePrefix.'OrderPayments', 'p')
          ->leftJoin('p', 'Modules', 'm', 'm.id = p.pay_id')
          ->where('p.pay_id > 0')
          ->execute()
          ->fetchAll();
        
        foreach ($sourceOrderpaymentModuleIds as $sourceOrderPaymentModule) {
            
            $hasMatch = false;
            foreach ($targetModules  as $targetModule) {
                if($targetModule['code'] == $sourceOrderPaymentModule['code']) {
                    $hasMatch = true;
                    $this->writeLn(sprintf('OrderPayment - Found Matching Module For: %s - %s To %s - %s', 
                        $sourceOrderPaymentModule['pay_id'], 
                        $sourceOrderPaymentModule['code'],
                        $targetModule['id'], 
                        $targetModule['code']
                    ));
                    
                    $updateQueryResult = $this->mivaQuery->getConnection()
                        ->createQueryBuilder()
                        ->update($this->mivaQuery->tablePrefix.'OrderPayments', 'p')
                        ->set('p.pay_id', '?')
                        ->where('p.pay_id = ?')
                        ->setParameter(0, $targetModule['id'])
                        ->setParameter(1, $sourceOrderPaymentModule['pay_id'])
                        ->execute();
                        
                    $this->writeLn(sprintf('OrderPayment - Updated %s Order Charges', $updateQueryResult));
                }
            }
            
            if (!$hasMatch) {
                $this->writeLn(sprintf('OrderPayment - Target Has No Matching Module For: %s - %s', 
                    $sourceOrderPaymentModule['pay_id'], 
                    $sourceOrderPaymentModule['code']
                ));
            }
        }
        
        
        $this->writeLn('Operation Completed.');
    }
}