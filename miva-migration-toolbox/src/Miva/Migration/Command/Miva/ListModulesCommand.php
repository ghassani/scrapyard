<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* ListModulesCommand
*
* List All Miva Merchant Modules Installed in the Store
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ListModulesCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('list-miva-modules')
            ->setDescription('List All Miva Merchant Modules Installed in the Store')
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = $this->mivaQuery->getModules();

        foreach($modules as $module){
            $features = $this->mivaQuery->getModuleFeaturesByCode($module['code']);

            $this->writeLn(sprintf('%s | %s by %s ',
                $module['code'],
                $module['name'],
                $module['provider']
            ));
            
            $this->writeLn(sprintf("\t".'Version: %s - API Version: %s Active: %s - Features: %s',
                $module['version'],
                $module['api_ver'],
                $module['active'] == 1 ? 'Yes' : 'No',
                implode(', ', array_map(function($v){ 
                    return $v['feature']; 
                }, $features))
            ));
        }

        $this->writeLn('Operation Completed.');
    }
}