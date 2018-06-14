<?php

namespace Miva\Migration\Command\Miva;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Command\BaseCommand as MainBaseCommand;
use Miva\Migration\Database\MivaQuery;

/**
* BaseCommand
*/
class BaseCommand extends MainBaseCommand
{

    protected $container;

    
    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this->addOption('target-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the target database.', 'miva');
    }

    /**
    * {@inheritDoc}
    */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
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
            '<question>What database connection belongs to the Target Miva Merchant installation? (default: miva)</question>',
            array_combine($connections, $connections),
            'miva'            
        );

        $input->setOption('target-connection', $targetConnection);

        $this->targetDB = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('target-connection', 'miva'));
        $this->mivaQuery = new MivaQuery($this->targetDB);
    }

 
}