<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* ProductsMigrateCommand
*
* Migrates Customer Data From Zen Cart Database to Miva Merchant Database
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
            ->setName('migrate-zencart-customers')
            ->setDescription('Migrates Customer Data From Zen Cart Database to Miva Merchant Database')            
            ->addOption('skip-existing', null, InputOption::VALUE_OPTIONAL, 'Skip Existing Orders', false);
    }
    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);


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
        $skipExisting = $input->getOption('skip-existing', false);

        $customers = $this->zenQuery->getCustomers();
        $existingLogins = array();

        foreach($customers as $k => $customer) {
            

            if(in_array($customer['email'], $existingLogins)){
                $this->writeLn(sprintf('Customer With Login %s Already Exists. Skipping', $customer['email']));
                continue;
            } 

            $targetCustomer = $this->mivaQuery->getCustomer($customer['id']);
            
            if($targetCustomer) {
                if($skipExisting === true) {
                    $this->writeLn(sprintf('%s/%s - Skipping Update on Customer With ID %s', ($k+1), count($customers), $customer['id']));
                    continue;
                }
                $isNew = false;
                $this->writeLn(sprintf('%s/%s - Updating Customer With ID %s', ($k+1), count($customers), $customer['id']));
            } else {
                $isNew = true;
                $targetCustomer = array();
                $this->writeLn(sprintf('%s/%s - Creating Customer With ID %s', ($k+1), count($customers), $customer['id']));
            }

            $customersLastOrder = $this->zenQuery->getLastOrderForCustomer($customer['id']);

            if($customersLastOrder) {
                $this->writeLn(sprintf('Customer %s Has Order History - Using Order ID %s Address Information ', $customer['id'], $customersLastOrder['id']));

                $shipName = $this->extractNameParts($customersLastOrder['ship_name']);
                $billName = $this->extractNameParts($customersLastOrder['bill_name']);

                $targetCustomer = array_merge($targetCustomer, array(
                    'id'         => $customer['id'],
                    'pgrpcount'  => 0,
                    'login'      => $customer['email'],
                    'pw_email'   => $customer['email'],
                    'password'   => $customer['password'],
                    'ship_fname' => $customer['fname'],
                    'ship_lname' => $customer['lname'],
                    'ship_email' => $customersLastOrder['ship_email'],
                    'ship_comp'  => $customersLastOrder['ship_comp'],
                    'ship_phone' => $customer['phone'],
                    'ship_fax'   => $customer['fax'],
                    'ship_addr'  => $customersLastOrder['ship_addr'],
                    'ship_addr2' => $customersLastOrder['ship_addr2'],
                    'ship_city'  => $customersLastOrder['ship_city'],
                    'ship_state' => $this->zoneNameToCode($customersLastOrder['ship_state']),
                    'ship_zip'   => $customersLastOrder['ship_zip'],
                    'ship_cntry' => $this->countryNameToCode($customersLastOrder['ship_cntry']),
                    'bill_fname' => $customer['fname'],
                    'bill_lname' => $customer['lname'],
                    'bill_email' => $customersLastOrder['bill_email'],
                    'bill_comp'  => $customersLastOrder['bill_comp'],
                    'bill_phone' => $customer['phone'],
                    'bill_fax'   => $customer['fax'],
                    'bill_addr'  => $customersLastOrder['bill_addr'],
                    'bill_addr2' => $customersLastOrder['bill_addr2'],
                    'bill_city'  => $customersLastOrder['bill_city'],
                    'bill_state' => $this->zoneNameToCode($customersLastOrder['bill_state']),
                    'bill_zip'   => $customersLastOrder['bill_zip'],
                    'bill_cntry' => $this->countryNameToCode($customersLastOrder['bill_cntry']),
                ));
                
            } else {

                #$this->writeLn(sprintf('Customer %s Has No Order History', $customer['id']));

                $targetCustomer = array_merge($targetCustomer, array(
                    'id'         => $customer['id'],
                    'pgrpcount'  => 0,
                    'login'      => $customer['email'],
                    'pw_email'   => $customer['email'],
                    'password'   => $customer['password'],
                    'ship_fname' => $customer['fname'],
                    'ship_lname' => $customer['lname'],
                    'ship_email' => $customer['email'],
                    'ship_comp'  => $customer['company'],
                    'ship_phone' => $customer['phone'],
                    'ship_fax'   => $customer['fax'],
                    'ship_addr'  => $customer['address'],
                    'ship_addr2' => $customer['address2'],
                    'ship_city'  => $customer['city'],
                    'ship_state' => $this->zoneNameToCode($customer['state']),
                    'ship_zip'   => $customer['zipcode'],
                    'ship_cntry' => $this->countryNameToCode($customer['country']),
                    'bill_fname' => $customer['default_address_fname'],
                    'bill_lname' => $customer['default_address_lname'],
                    'bill_email' => $customer['email'],
                    'bill_comp'  => $customer['default_company'],
                    'bill_phone' => $customer['phone'],
                    'bill_fax'   => $customer['fax'],
                    'bill_addr'  => $customer['default_address'],
                    'bill_addr2' => $customer['default_address2'],
                    'bill_city'  => $customer['default_city'], 
                    'bill_state' => $this->zoneNameToCode($customer['default_state']),
                    'bill_zip'   => $customer['default_zipcode'],
                    'bill_cntry' => $this->countryNameToCode($customer['default_country']),
                ));   
            }

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

            array_push($existingLogins, $customer['email']);
        }

        $this->writeLn('Operation Completed.');
    }

    /**
     * extractNameParts
     * 
     * @param mixed $str Description.
     *
     * @access protected
     *
     * @return array
     */
    protected function extractNameParts($str)
    {
        $parts = explode(' ', $str);

        if(count($parts) == 1) {
            $firstName = $parts[0];
            $lastName = null;
        } elseif (count($parts) == 2) {
            $firstName = $parts[0];
            $lastName = $parts[1];
        } else {
            $firstName = $parts[0];
            unset($parts[0]);            
            $lastName = implode(' ', $parts);            
        }
 
        return array(
            'first' => $firstName,
            'last' => $lastName
        );
    }


    /**
     * zoneNameToCode
     * 
     * @param mixed $str Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function zoneNameToCode($str)
    {
        if (!isset($this->zones)) {
            $this->zones = $this->zenQuery->getZones();
        }

        $str = strtolower(trim($str));

        foreach ($this->zones as $zone) {
            if($str == strtolower($zone['zone_name']) || $str == strtolower($zone['zone_code'])) {                
                return $zone['zone_code'];
            }
        } 

        return $str;
    }

    /**
     * countryNameToCode
     * 
     * @param mixed $str Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function countryNameToCode($str)
    {
        if(!isset($this->countries)) {
            $this->countries = $this->zenQuery->getCountries();
        }
        foreach ($this->countries as $country) {
            if ($str == $country['countries_name'] || $str == $country['countries_iso_code_2']) {
                return $country['countries_iso_code_2'];
            }
        }

        return $str;
    }
}