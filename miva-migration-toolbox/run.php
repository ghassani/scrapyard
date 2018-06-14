<?php
require_once(dirname(__FILE__).'/include/bootstrap.php');

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$startingCommand = isset($argv[1]) ? $argv[1] : null;

if(is_null($startingCommand)){
	if(isset($configuration['startup']['start_command'])){
		$startingCommand = $configuration['startup']['start_command'];
	} else {
		$startingCommand = 'start';
	}
}

$argv[1] = $startingCommand;

$application->run(new ArgvInput($argv), new ConsoleOutput());