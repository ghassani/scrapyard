<?php

namespace Miva\Migration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use ForceUTF8\Encoding AS UTF8Encoder;

/**
* BaseCommand
*/
class BaseCommand extends Command
{

    const COMMAND_SUCCESS = 0;
    const COMMAND_ERROR = 1;
    
    protected $container;

    
    /**
    * {@inheritDoc}
    */
    protected function configure()
    {
        parent::configure();
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
    }

    /**
     * getMemoryUsage
     * 
     * @access protected
     *
     * @return mixed Value.
     */
    protected function getMemoryUsage()
    {
        return number_format((memory_get_usage()/1048576));
    }

    /**
     * writeLn
     * 
     * @param mixed $message Description.
     *
     * @access protected
     *
     * @return mixed Value.
     */
    protected function writeLn($message, $showMemory = true)
    {
        global $logging;
        global $showInfoMessages;

        if(true == $logging && (!isset($this->logger) || !$this->logger instanceof Logger)){
            $this->logger = new Logger('miva-migration');
            $this->logger->pushHandler(new StreamHandler(LOG_DIR.'/operation_log.txt', Logger::INFO));
        }
        
        if(true === $showInfoMessages){
            if($showMemory) {
                $this->output->writeLn(sprintf('%sMB | %s', $this->getMemoryUsage(), $message));
            } else {
                $this->output->writeLn($message);
            }
        }
        
        if(true == $logging && isset($this->logger) && $this->logger instanceof Logger){
            $this->logger->addInfo($message);
        }
    }

    /**
    *
    */
    public function getServiceContainer()
    {
        return $this->container;
    }

    /**
    *
    */
    public function setServiceContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    public function toUTF8($str)
    {
        return UTF8Encoder::fixUTF8(UTF8Encoder::toUTF8($str));
    }

    public function cleanValue($value, $addToRemove = array())
    {
        if(!is_array($addToRemove)){
            $addToRemove = array();
        }
        $baseRemove = array("\n","\t","\r","\\u00a0","\xa0","\u00a0");
        $_value = str_replace(array_merge($addToRemove,$baseRemove),' ',$value);
        $_value = preg_replace('/[^(\x20-\x7F)]*/','', $_value);
        $_value = preg_replace('/\s{2,}/', ' ',$_value); 

        return trim($_value);
    }

}