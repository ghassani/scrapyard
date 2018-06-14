<?php

namespace Miva\Migration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArgvInput;

/**
* InteractiveMigrationCommand
*
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class InteractiveMigrationCommand extends BaseCommand
{

    const DEFAULT_INCREMENT = 1;

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('start')
            ->setDescription('');
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->printInformation($output);

        return $this->mainScreen($input, $output);
    }

    /**
     * mainScreen
     * 
     * @param mixed \InputInterface   Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function mainScreen(InputInterface $input, OutputInterface $output)
    {

        $this->printHeader('Main Menu', $output);

        global $logging;
        global $showInfoMessages;

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation Section',
            array(
                1 => 'Miva Merchant Operations',
                2 => 'Zen Cart Migration Operations',
                3 => 'eBay ProStores Migration Operations',
                4 => 'Other Operations',
                5 => 'Custom Tasks',
                6 => 'Toggle Logging. Currently: '.($logging ? 'On' : 'Off'),
                7 => 'Toggle Informational Message Output. Currently: '.($showInfoMessages ? 'On' : 'Off'),
                8 => 'Quit',
            ),
            0
        );

        switch($selection) {
            default:
            case 1:
                return $this->mivaScreen($input, $output);
                break;
            case 2:
                return $this->zenScreen($input, $output);
                break;
            case 3:
                return $this->prostoresScreen($input, $output);
                break;
            case 4:
                return $this->otherScreen($input, $output);
                break;
            case 5:
                return $this->customTasks($input, $output);
                break;
            case 6:
                $logging = $logging == true ? false : true;
                return $this->mainScreen($input, $output);
                break;
            case 7:
                $showInfoMessages = $showInfoMessages == true ? false : true;
                return $this->mainScreen($input, $output);
                break;                
            case 8:
                return 0;
                break;
        }
    }

    /**
     * prostoresScreen
     * 
     * @param mixed \InputInterface Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function prostoresScreen(InputInterface $input, OutputInterface $output)
    {
        $this->printHeader('eBay Pro Stores Migration Operations', $output);

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation',
            array(
                1 => 'Migrate Customers to XML Provisioning',
                2 => 'Migrate Products to XML Provisioning',
                3 => 'Migrate Product Images to Additional Images with XML Provisioning',
                4 => 'Migrate Product Dimensions and Box Sizes to XML Provisioning',
                5 => 'Migrate Products to Update XML Provisioning (Basic Product Data)',
                6 => 'Migrate Product Inventory Levels to XML Provisioning',
                7 => 'Migrate Customers  (DB Write)',
                8 => 'Migrate Affiliates (DB Write)',
                9 => 'Back To Main',
            ),
            0
        );
       
    
    
        switch($selection) { 
            case 1:
                $command = $this->getApplication()->find('prostores-customers-to-provision-xml');
                break;
            case 2:
                $command = $this->getApplication()->find('prostores-products-to-provision-xml');
                break;
            case 3:
                $command = $this->getApplication()->find('prostores-product-images-to-additional-images-provision-xml');
                break;
            case 4:
                $command = $this->getApplication()->find('prostores-product-dimensions-to-boxes-provision-xml');
                break;
            case 5:
                $command = $this->getApplication()->find('prostores-products-to-update-provision-xml');
                break;                
            case 6:
                $command = $this->getApplication()->find('prostores-products-inventory-to-provision-xml');
                break;
                  
            case 7:
                $command = $this->getApplication()->find('prostores-customers-migrate');
                break;
            case 8:
                $command = $this->getApplication()->find('prostores-affiliate-migrate');
                break; 
            case 9:
                return $this->mainScreen($input, $output);
                break;
        }

        if(isset($command)){
            $commandInput = new ArgvInput(array(null, $command->getName()));
            $command->run($commandInput, $output);
        }

        return $this->prostoresScreen($input, $output);
    }

    /**
     * zenScreen
     * 
     * @param mixed \InputInterface   Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function zenScreen(InputInterface $input, OutputInterface $output)
    {

        $this->printHeader('Zen Cart Migration Operations', $output);

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation',
            array(
                1 => 'Migrate Categories (DB Write)',
                2 => 'Migrate Products   (DB Write)',
                3 => 'Migrate Customers  (DB Write)',
                4 => 'Migrate Orders     (DB Write)',
                5 => 'Migrate Manufacturers to Categories (DB Write)',
                6 => 'Download Product Images to Local Filesystem',
                7 => 'Migrate Reviews to Sebenza Reviews (DB Write)',
                8 => 'Migrate Inventory (DB Write)',
                9 => 'Back To Main',
            ),
            0
        );

        

        switch($selection) {
            default:
            case 1:
                $command = $this->getApplication()->find('migrate-zencart-categories');
                break;
            case 2:
                $command = $this->getApplication()->find('migrate-zencart-products');
                break;
            case 3:
                $command = $this->getApplication()->find('migrate-zencart-customers');
                break;
            case 4:
                $command = $this->getApplication()->find('migrate-zencart-orders');
                break;
            case 5:
                $command = $this->getApplication()->find('migrate-zencart-manufacturers-to-categories');
                break;  
            case 6:
                $command = $this->getApplication()->find('migrate-zencart-download-product-images');
                break;  
            case 7:
                $command = $this->getApplication()->find('migrate-zencart-reviews-to-sebenza');
                break;
            case 8:
                $command = $this->getApplication()->find('migrate-zencart-inventory');
                break;
            case 9:
                return $this->mainScreen($input, $output);
                break;
        }

        if(isset($command)){
            $commandInput = new ArgvInput(array(null, $command->getName()));
            $command->run($commandInput, $output);
        }

        return $this->zenScreen($input, $output);
    }


    /**
     * customTasks
     * 
     * @param mixed \InputInterface   Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function customTasks(InputInterface $input, OutputInterface $output)
    {
        $this->printHeader('Custom Tasks', $output);

        $userConfig = $this->getServiceContainer()->getParameter('user_configuration');

        $userCommands = array();

        $choice = 0;

        foreach($userConfig['commands'] as $name => $userCommandClass) {
            if($this->getApplication()->has($name)){
                $_command = $this->getApplication()->get($name);
                $userCommands[++$choice] = $_command->getName().' - '.$_command->getDescription();
                $_userCommands[$choice] = $_command;
            }
        }


        
        $userCommands[++$choice] = 'Back to Main';

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation',
            $userCommands,
            0
        );


        switch($selection) {
            default:
                if(isset($_userCommands[$selection])) {
                    $command = $_userCommands[$selection];
                }
                break;
            case count($userCommands):
                return $this->mainScreen($input, $output);
                break;
        }

        if(isset($command)){
            $commandInput = new ArgvInput(array(null, $command->getName()));
            $command->run($commandInput, $output);
        }
        
        return $this->customTasks($input, $output);

    }


    /**
     * mivaScreen
     * 
     * @param mixed \InputInterface   Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function mivaScreen(InputInterface $input, OutputInterface $output)
    {

        $this->printHeader('Miva Merchant Operations', $output);

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation',
            array(
                1 => 'Increment Store Keys',
                2 => 'List Installed Modules',
                3 => 'Update PageFinder Links',
                4 => 'Copy Products From One Miva Database to Another',
                5 => 'Back To Main',
            ),
            0
        );

        switch($selection) {
            default:
            case 1:
                $command = $this->getApplication()->find('increment-miva-store-keys');
                break;
            case 2:
                $command = $this->getApplication()->find('list-miva-modules');
                break;
            case 3:
                $command = $this->getApplication()->find('rebuild-miva-pagefinder-links');
                break;
            case 4:
                $command = $this->getApplication()->find('copy-products-miva-to-miva');
                break;
            case 5:
                return $this->mainScreen($input, $output);
                break;
        }

        if(isset($command)){
            $commandInput = new ArgvInput(array(null, $command->getName()));
            $command->run($commandInput, $output);
        }

        return $this->mivaScreen($input, $output);
    }

    /**
     * otherScreen
     * 
     * @param mixed \InputInterface   Description.
     * @param mixed \OutputInterface Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function otherScreen(InputInterface $input, OutputInterface $output)
    {

        $this->printHeader('Other Operations', $output);

        $selection = $this->getHelper('dialog')->select(
            $output,
            'Select an Operation',
            array(
                1 => 'Available Database Connections',
                2 => 'Available Commands',
                3 => 'Dump Database to SQL',
                4 => 'Chunk a CSV into Multiple Files',
                5 => 'Application Information',
                6 => 'Back To Main',
            ),
            0
        );

        switch($selection) {
            default:
            case 1:
                $command = $this->getApplication()->find('available-connections');
                break;
            case 2:
                $command = $this->getApplication()->find('list');
                break;
            case 3:
                $command = $this->getApplication()->find('dump-database-sql');
                break;
            case 4:
                $command = $this->getApplication()->find('chunk-csv');
                break;
            case 5:
                $this->printInformation($output);
                break;
            case 6:
                return $this->mainScreen($input, $output);
                break;
        }

        if(isset($command)){
            $commandInput = new ArgvInput(array(null, $command->getName()));
            $command->run($commandInput, $output);
        }

        return $this->otherScreen($input, $output);
    }

    /**
     * printHeader
     * 
     * @param mixed $header Description.
     * @param mixed $output Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function printHeader($header, $output)
    {
        $lineLength = strlen('--------------------------------------------------------');
        $memoryUsage = '| Memory: '.$this->getMemoryUsage().'MB |';
        $memoryUsageLength = strlen($memoryUsage)+1;
        $_header = "| > ".$header;
        for($i = strlen($_header); $i < ($lineLength-$memoryUsageLength)+2; $i++){
            $_header .= ' ';
        }

        $output->writeLn(PHP_EOL);
        $output->writeLn('---------------------------------------------------------');
        $output->writeLn($_header.$memoryUsage);
        $output->writeLn('---------------------------------------------------------');
        $output->writeLn(PHP_EOL);
    }

    /**
     * printInformation
     * 
     * @param mixed $output Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function printInformation($output)
    {
        $output->writeLn(PHP_EOL);
        $output->writeLn('---------------------------------------------------------');
        $output->writeLn('| Miva Merchant  5.5 - 9.0                              |');
        $output->writeLn('| Interactive Migration Tool                            |');
        $output->writeLn('---------------------------------------------------------');
        $output->writeLn('| Copyright 2014 - 2015 Miva Inc.                       |');
        $output->writeLn('| Author Gassan Idriss <gidriss@miva.com>               |');
        $output->writeLn('---------------------------------------------------------');
    }
}
