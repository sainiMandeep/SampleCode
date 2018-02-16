<?php
error_reporting(E_ALL | E_STRICT);


// Define path to application directory
if (! defined('APPLICATION_PATH'))
	define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

define("UNITTEST", "UNITTEST");

$GLOBALS['RECOVERY_IP_OPEN'] = array('127.0.0.1');
$_SERVER['SERVER_NAME'] = 'http://vendor';
$_SERVER['SERVER_ADDR'] = '127.0.0.1'; // Vendor by default


// Define application environment
defined('APP_ENV')
		|| define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'TESTING'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library'),
	get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


/** Zend_Application */
require_once 'Zend/Application.php';

require_once 'ControllerTestCase.php';