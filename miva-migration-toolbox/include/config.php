<?php
define('APPLICATION_NAME', 'Miva Merchant Migration Tool');
define('APPLICATION_VERSION', 1.0);
define('DEBUG', true);
define('POSIX', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? false : true);
define('WIN32', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? true : false);
define('BASE_DIR', dirname(__FILE__).'/../');
define('EXPORTS_DIR', BASE_DIR.'/exports/');
define('IMPORTS_DIR', BASE_DIR.'/imports/');
define('BIN_DIR', BASE_DIR.'/bin/'.(defined('WIN32') && WIN32 ? 'win32' : 'unix').'/');
define('LOG_DIR', BASE_DIR.'/logs/');
define('LIB_DIR', BASE_DIR.'/lib/');

define('TARGET_DB_PREFIX', 's01_'); 

if(defined('DEBUG') && DEBUG === true){
	error_reporting(E_ALL ^ E_DEPRECATED);
	ini_set('display_errors', 'On');
} 	

$connections = array(
	'zen' => array(
		'host' => 'localhost',
		'database' => 'zen_migrate',
		'username' => 'root',
		'password' => '',
		'table_prefix' => '',
	),
	'miva' => array(
		'host' => 'localhost',
		'database' => 'mm5_test',
		'username' => 'root',
		'password' => '',
		'table_prefix' => 's01_',
	),
	'miva_sbt' => array(
		'host' => 'localhost',
		'database' => 'sbt',
		'username' => 'root',
		'password' => '',
		'table_prefix' => 's01_',
	),
	'miva_acoustic' => array(
		'host' => 'localhost',
		'database' => 'acousticaudio',
		'username' => 'root',
		'password' => '',
		'table_prefix' => 's01_',
	),
	'stardust' => array(
		'host' => 'localhost',
		'database' => 'stardust',
		'username' => 'root',
		'password' => '',
		'table_prefix' => 's01_',
	)
);


