<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * BaseCommand
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class BaseCommand extends ContainerAwareCommand
{
    
    const OPERATION_SUCCESS = 0;


    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->output = $output;
        $this->kernel = $this->getContainer()->get('kernel');
        $this->filesystem = $this->getContainer()->get('filesystem');
        //$this->request = $this->getContainer()->get('request');
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();

        // create a log for the current operation
        $logFileName = explode('\\',get_class($this));
        $logFileName = \end($logFileName);
        $logFilePath = sprintf('%s/logs/%s-%s.log',$this->kernel->getRootDir(),$logFileName, time());

        $this->log = new Logger($logFileName);
        $this->log->pushHandler(new StreamHandler($logFilePath, Logger::INFO));
        $this->log->pushHandler(new StreamHandler($logFilePath, Logger::ERROR));
    }

    protected function configure()
    {
        parent::configure();

    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        parent::execute($input,$output);
    }

    /**
     *
     */
    public function getImagesFromFolder($folderPath)
    {
        $return = array();
        if (file_exists($folderPath) && is_dir($folderPath)) {
            $folderHandle = opendir($folderPath);

            while (($fileName = readdir($folderHandle)) !== false) {
                if (preg_match('/\.(png|jpg|jpeg|gif)$/i',$fileName)) {
                    $return[] = $fileName;
                }
            }
            closedir($folderHandle);
        }

        return $return;
    }
    /**
     * getMemoryUsage
     */
    protected function getMemoryUsage()
    {
        return number_format((memory_get_usage()/1048576));
    }
    /**
    * getRequestTime
    */
    protected function getRequestTime()
    {
        return number_format(((time() - $_SERVER["REQUEST_TIME"])/60)/60);
    }
    
    /**
     *
     */
    protected function writeLine($msg, $exit = false)
    {
        $this->output->writeln(sprintf('%sMB | %s',$this->getMemoryUsage(),$msg));

        if (true === $exit) {
            if($this->log){
                $this->log->addError($msg);
            }
            die();
        }
        
        if($this->log){
            $this->log->addInfo($msg);
        }
    }
    
    
}
