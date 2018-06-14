<?php

namespace Miva\Migration\Command\Zencart;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Miva\Migration\Command\Miva\BaseCommand as MivaBaseCommand;
use Miva\Migration\Database\ZenQuery;

/**
* BaseCommand
*
* 
*/
class BaseCommand extends MivaBaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this->addOption('zen-connection', null, InputOption::VALUE_OPTIONAL, 'Database Connection Name to use as the source Zen Cart Database.', 'zen');
    }

    /**
    * {@inheritDoc}
    */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

    }

    
    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $zenConnection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection belongs to the Zen Cart installation? (default: zen)</question>',
            array_combine($connections, $connections),
            'zen'
        );

        $input->setOption('zen-connection', $zenConnection);

        $this->zenDb = $this->getServiceContainer()->get('database_manager')->getConnection($input->getOption('zen-connection', 'zen'));
        $this->zenQuery = new ZenQuery($this->zenDb);

    }
}