<?php

namespace Miva\Migration\Command\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Miva\Migration\Command\BaseCommand;

/**
* DumpTablesToSqlCommand
*
* Dump All Database Tables to SQL For Import To Another Database
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class DumpTablesToSqlCommand extends BaseCommand
{

    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('dump-database-sql')
            ->setDescription('Dump All Database Tables to SQL For Import To Another Database')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'Connection To Use', null)
            ->addOption('chunked', null, InputOption::VALUE_OPTIONAL, 'Output each table to its own file', true)
            ->addOption('drop-table', null, InputOption::VALUE_OPTIONAL, 'Add DROP TABLE query', true)
            ->addOption('compress', null, InputOption::VALUE_OPTIONAL, 'Compress Outputted Files', true)
        ;
    }

    /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $connections = $this->getServiceContainer()->get('database_manager')->getAvailableConnections();

        $connection = $this->getHelper('dialog')->select(
            $output,
            '<question>What database connection to dump?</question>',
            array_combine($connections, $connections),
            null
        );        

        $chunked = $this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Dump each table to an individual file?  (default: yes) </question>', 
            true
        );

        $dropTable = $this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Add drop table query to the dump?  (default: yes) </question>', 
            true
        );

        $compress = $this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Compress the output file(s)?  (default: yes) </question>', 
            true
        );

        $input->setOption('connection', $connection);
        $input->setOption('chunked', $chunked);
        $input->setOption('drop-table', $dropTable);
        $input->setOption('compress', $compress);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $connection = $this->getServiceContainer()->get('database_manager')
          ->getConnection($input->getOption('connection'));

        $database           = $connection->getDatabase();
        $databaseHost       = $connection->getHost();
        $databasePort       = $connection->getPort();
        $databaseUsername   = $connection->getUsername();
        $databasePassword   = $connection->getPassword();
        $chunked            = $input->getOption('chunked');
        $dropTable          = $input->getOption('drop-table');
        $compress           = $input->getOption('compress');

        $baseExportDir = sprintf('%ssql/%s-%s/',EXPORTS_DIR,$input->getOption('connection'), $database);

        if(!file_exists($baseExportDir)){
            mkdir($baseExportDir, 0755);
        }

        if($chunked){
            $tablesQuery = $connection->prepare('SHOW TABLES');

            $tablesQuery->execute();
            $tables = $tablesQuery->fetchAll();

            foreach($tables as $table){
                $tableName = $table[0];

                $processCommand = preg_replace('/\s{2,}/', ' ', sprintf('mysqldump %s %s %s %s %s %s %s > %s',
                    $databaseUsername ? ' -u '.$databaseUsername : null,
                    $databasePassword ? ' -p'.$databasePassword : null,
                    '--host='.$databaseHost,
                    '--port='.$databasePort,
                    $dropTable ? '--add-drop-table' : null,
                    $database,
                    $tableName,
                    $baseExportDir.$tableName.'.sql'               
                ));

                $process = new Process($processCommand);
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new \RuntimeException($process->getErrorOutput());
                }


                $this->writeLn(sprintf('Table %s Dumped', $tableName));

                if($compress){
                    if(defined('WIN32') && WIN32){
                        $compressCommand = sprintf('%s7z.exe a %s %s', 
                            BIN_DIR, 
                            $baseExportDir.$tableName.'.sql.zip', 
                            $baseExportDir.$tableName.'.sql'
                        );
                    } else {
                        $compressCommand = sprintf('tar -zcvf %s', 
                            BIN_DIR, 
                            $baseExportDir.$tableName.'.sql.tar.gz', 
                            $baseExportDir.$tableName.'.sql'
                        );
                    }
                             
                    $compressProcess = new Process($compressCommand);
                    $compressProcess->run();

                    // executes after the command finishes
                    if (!$compressProcess->isSuccessful()) {
                        throw new \RuntimeException($compressProcess->getErrorOutput());
                    }

                    $this->writeLn(sprintf('Table Dump For %s Compressed', $tableName));
                }
            }
        } else {
            $processCommand = preg_replace('/\s{2,}/', ' ', sprintf('mysqldump %s %s %s %s %s %s > %s',
                $databaseUsername ? ' -u '.$databaseUsername : null,
                $databasePassword ? ' -p'.$databasePassword : null,
                '--host='.$databaseHost,
                '--port='.$databasePort,
                $dropTable ? '--add-drop-table' : null,
                $database,
                $baseExportDir.$database.'.sql'
            ));

            $process = new Process($processCommand);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new \RuntimeException($process->getErrorOutput());
            }

            if($compress){
                if(defined('WIN32') && WIN32){
                    $compressCommand = sprintf('%s7z.exe a %s %s', 
                        BIN_DIR, 
                        $baseExportDir.$database.'.sql.zip',
                        $baseExportDir.$database.'.sql'
                    );
                } else {
                    $compressCommand = sprintf('tar -zcvf %s', 
                        BIN_DIR, 
                        $baseExportDir.$database.'.sql.tar.gz',
                        $baseExportDir.$database.'.sql'
                    );
                }
                         
                $compressProcess = new Process($compressCommand);
                $compressProcess->run();

                // executes after the command finishes
                if (!$compressProcess->isSuccessful()) {
                    throw new \RuntimeException($compressProcess->getErrorOutput());
                }
            }
        }

        $this->writeLn('Operation Completed.');
    }
}