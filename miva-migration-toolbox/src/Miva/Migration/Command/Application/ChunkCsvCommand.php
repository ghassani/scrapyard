<?php

namespace Miva\Migration\Command\Application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Miva\Migration\Command\BaseCommand;
use Symfony\Component\Finder\Finder;

/**
* ChunkCsvCommand
*
*
* @author Gassan Idriss <gidriss@mivamerchant.com>
*/
class ChunkCsvCommand extends BaseCommand
{


    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('chunk-csv')
            ->setDescription('Take a large CSV file and split it up into multiple files')
            ->addOption('chunks', null, InputOption::VALUE_REQUIRED, 'How many records to split the file into', 10000)
            ->addOption('has-header', null, InputOption::VALUE_REQUIRED, 'If the first row is a header', true)
            ->addOption('out-file', null, InputOption::VALUE_REQUIRED, 'Optionally specify a filename to export as. Defaults to out<#>.csv ', 'out.csv')
            ->addOption('in-file', null, InputOption::VALUE_REQUIRED, 'Required Option. Specify a filename to import with.')
            ;
    }
 /**
    * {@inheritDoc}
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
                
        $finder = new Finder();

        $importDir = new \SplFileInfo(IMPORTS_DIR);
        $exportDir = new \SplFileInfo(EXPORTS_DIR);

        $exportFiles = $finder->files()
          ->depth(1)
          ->name('/\.(csv|txt)$/')
          ->in($importDir->getRealPath());

        
        $files = array();

        $k = 1;
        foreach ($exportFiles as $exportFile) {
            $relativePath = str_replace($importDir->getRealPath().DIRECTORY_SEPARATOR, '', $exportFile->getRealPath());
            $files[$k] = $relativePath;
            $k++;
        }

        $inFile = $this->getHelper('dialog')->select(
            $output,
            '<question>Which file to chunk out?</question>',
            $files,
            null
        );
        
        $input->setOption('in-file', $files[$inFile]);
        
        $outFile = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>What file name to export the CSV files to? File will be dumped in %s:</question>', $exportDir->getRealPath()),
            function ($fileName) {
                if(!$fileName) {
                  return 'out.csv';
                }
                return $fileName;
            },
            false,
            'out.csv'
        );

        $input->setOption('out-file', $outFile);
        
        $chunks = $this->getHelper('dialog')->askAndValidate(
            $output,
            sprintf('<question>How many records to split the file into? Default is %s:</question>', $input->getOption('chunks')),
            function ($chunks) {
                if(!is_int($chunks)) {
                  throw new \InvalidArgumentException('Must be a number. Try again');
                }
                return $chunks;
            },
            false,
            10000
        );

        $input->setOption('chunks', $chunks);

        $hasHeader = $this->getHelper('dialog')->askConfirmation($output,'<question>Does the first row contain a header?</question>', true);
        
        $input->setOption('has-header', $hasHeader);
    }

    /**
    * {@inheritDoc}
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $inFile      = new \SplFileInfo(sprintf('%s/%s', IMPORTS_DIR, $input->getOption('in-file')));
        $outFile     = sprintf('%s/%s', EXPORTS_DIR, $input->getOption('out-file'));
        $chunks      = $input->getOption('chunks', 10000);
        $hasHeader   = $input->getOption('has-header', 10000);
        
        if (!file_exists($inFile->getRealPath())) {
            $this->writeLn(sprintf('Could Not Find Input File %s', $inFile));
            return static::COMMAND_ERROR;
        }

        if(is_dir($outFile)) {
            $this->writeLn(sprintf('Output File Ended Up Being a Directory %s', $outFile->getRealPath()));
            return static::COMMAND_ERROR;
        }
        

        $fh = fopen($inFile->getRealPath(), 'r');
        
        $i = 0;
        $header = null;
        $currentChunk = 1;
        $currentInChunk = 1;        
        $ofh = fopen($outFile, 'a+');
        
        while(($row = fgetcsv($fh))){
            
            if($hasHeader && $i == 0){
                $this->writeLn('Saving Header and Continuing');
                $header = $row;
                fputcsv($ofh, $row);
                $i++;
                continue;
            }
            
            if ($currentInChunk == $chunks) {
                $this->writeLn(sprintf('Starting New Chunk - #%s', $currentChunk));
                // start a new file to write to
                $currentChunk++;
                $currentInChunk = 0;
                fclose($ofh);
                $ofh = fopen(preg_replace('/\.(.{1,5})$/', sprintf('%s.$1', $currentChunk), $outFile), 'a+');
                if ($hasHeader && isset($header)) {
                    fputcsv($ofh, $header);
                }
                
            }
            
            fputcsv($ofh, $row);
            $i++;
            $currentInChunk++;
        }
        
        fclose($fh);
        fclose($ofh);
    }

}
