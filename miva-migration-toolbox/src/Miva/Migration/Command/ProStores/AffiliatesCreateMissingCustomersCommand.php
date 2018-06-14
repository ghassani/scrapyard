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
* AffiliatesCreateMissingCustomersCommand
*
* Find missing associations to customers from affiliate import
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class AffiliatesCreateMissingCustomersCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('prostores-affiliate-create-missing-customers')
            ->setDescription('Find missing associations to customers from affiliate import')
            ->addOption('target-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the target database.', 'miva')                       
        ;

    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
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

    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $affiliates = $this->mivaQuery->getConnection()
          ->createQueryBuilder()
          ->select('*')
          ->from($this->mivaQuery->tablePrefix.'brokaffil_affiliates1')
          ->execute()
          ->fetchAll(); 

        $this->writeLn(sprintf('Loaded %s Affiliates With No Customer Relation', count($affiliates)));

        $currentCustomerId = $this->mivaQuery->currentIdAbstract('Customers', 'id', true);

        $addedLogins = array();

        foreach ($affiliates as $affiliate) {
        	$customer 	 = $this->mivaQuery->getCustomerBy(array('login','ship_email','bill_email'), $affiliate['email']);
        	
        	if ($customer) {
        	    
                if ($affiliate['cust_id']) {
                    if (!filter_var($affiliate['email'], FILTER_VALIDATE_EMAIL)) {
                        $this->writeLn(sprintf('Found Invalid Email Customer Account For: %s - Deleting Customer Account %s',
                            $affiliate['email'],
                            $customer['id']
                        ));     
                        
                        
                        $this->mivaQuery->deleteCustomer($customer);   
                        $this->mivaQuery->deleteVikingCoderAffiliate($affiliate);   
                    }
                } else {
            		$this->writeLn(sprintf('Found Customer Account For: %s',
            			$affiliate['email']
            		));        
    
            		$affiliate['cust_id'] = $customer['id'];
    
            		$this->mivaQuery->updateVikingCoderAffiliate($affiliate);	
                }
        		continue;
        	}
            
        	if (in_array($affiliate['email'], $addedLogins)) {        	    
        		continue;
        	}
            
            if (!filter_var($affiliate['email'], FILTER_VALIDATE_EMAIL)) {
                $this->writeLn(sprintf('Invalid Email Address: %s - Deleting Affiliate ID %s', $affiliate['email'], $affiliate['affiliate']));
                $this->mivaQuery->deleteVikingCoderAffiliate($affiliate); 
                continue;
            }

        	// create a new customer
        	$customer = array(
                'id'         => ++$currentCustomerId,// TODO
                'pgrpcount'  => 0,
                'login'      => $affiliate['email'],
                'pw_email'   => $affiliate['email'],
                'password'   => md5(rand(1000000,2000000)),
                'ship_fname' => $affiliate['fname'],
                'ship_lname' => $affiliate['lname'],
                'ship_email' => $affiliate['email'],
                'ship_comp'  => $affiliate['company'],
                'ship_phone' => $affiliate['phone1'],
                'ship_fax'   => $affiliate['phone2'],
                'ship_addr'  => $affiliate['address'],
                'ship_addr2' => null,
                'ship_city'  => $affiliate['city'],
                'ship_state' => $affiliate['state'],
                'ship_zip'   => $affiliate['zip'],
                'ship_cntry' => $affiliate['country'],
                'bill_fname' => $affiliate['fname'],
                'bill_lname' => $affiliate['lname'],
                'bill_email' => $affiliate['email'],
                'bill_comp'  => $affiliate['company'],
                'bill_phone' => $affiliate['phone1'],
                'bill_fax'   => $affiliate['phone2'],
                'bill_addr'  => $affiliate['address'],
                'bill_addr2' => null,
                'bill_city'  => $affiliate['city'], 
                'bill_state' => $affiliate['state'],
                'bill_zip'   => $affiliate['zip'],
                'bill_cntry' => $affiliate['country'],
            );  

			$affiliate['cust_id'] = $customer['id'];

			$this->writeLn(sprintf('Associated Affiliate ID %s With New Account ID %s With Login %s And Password %s',
				$affiliate['affiliate'],
				$customer['id'],
				$customer['login'],
				$customer['password']
			));

			$addedLogins[] = $customer['login'];

			$this->mivaQuery->insertCustomer($customer);
			$this->mivaQuery->updateVikingCoderAffiliate($affiliate);
        }
        $this->writeLn('Operation Completed.');
    }

}