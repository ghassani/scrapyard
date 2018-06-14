<?php

namespace Miva\Migration\Command\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Miva\Migration\Command\BaseCommand;

/**
* AvailableConnectionsCommand
*
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class AvailableConnectionsCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('available-connections')
            ->setDescription('');
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $output->writeLn('Available Connections:');
        foreach($connections as $key => $connection){
            $output->writeLn("\t$key) ".$connection);
        }
        return 1;
    }

}
