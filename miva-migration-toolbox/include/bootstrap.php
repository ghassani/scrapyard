<?php
require_once(dirname(__FILE__).'/../vendor/autoload.php');
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/functions.php');

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration as DBALConfiguration;
use Symfony\Component\Yaml\Parser;

$yaml             		= new Parser();
$serviceContainer 		= new ContainerBuilder();
$baseConfiguration    = $yaml->parse(file_get_contents(dirname(__FILE__).'/config.yml'));
$userConfiguration    = $yaml->parse(file_get_contents(BASE_DIR.'/config.yml'));

$configuration = array_merge_recursive($baseConfiguration, $userConfiguration);

$serviceContainer->setParameter('configuration', 		$configuration);
$serviceContainer->setParameter('base_configuration', 	$baseConfiguration);
$serviceContainer->setParameter('user_configuration', 	$userConfiguration);

foreach($configuration['services'] as $_service){
	$service = $serviceContainer->register($_service['id'], $_service['class']);
	if(isset($_service['arguments']) && is_array($_service['arguments'])){
		foreach($_service['arguments'] as $argument){
			if($argument['is_reference']) {
				$service->addArgument(new Reference($argument['value']));
			} else {
				$service->addArgument('%connections%');
			}
		}
	}	
}

foreach($configuration['connections'] as $settings){
	$connection = DriverManager::getConnection($settings['params'], new DBALConfiguration());

	if($serviceContainer->has('database_manager')) { 
		$serviceContainer->get('database_manager')->addConnection($settings['name'], $connection);
	}
	
	$serviceContainer->set('connection.'.$settings['name'], $connection);
}

$application = new Application(APPLICATION_NAME, APPLICATION_VERSION);

$logging = isset($configuration['startup']['enable_logging']) ? $configuration['startup']['enable_logging'] : false;
$showInfoMessages = isset($configuration['startup']['enable_output']) ? $configuration['startup']['enable_output'] : true;

foreach($configuration['commands'] as $class){

	$command = new $class();
	if(method_exists($command, 'setServiceContainer')){
		$command->setServiceContainer($serviceContainer);
	}

	$application->add($command);
}

