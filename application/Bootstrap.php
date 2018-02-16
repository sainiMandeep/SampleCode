<?php

require_once(APPLICATION_PATH.'/../server-vars.php');

if(!defined("UNITTEST") && session_id() == ''){
	Zend_Session::start();
}

Zend_Loader_Autoloader::getInstance()->registerNamespace('Ontraq_');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Recovery_');

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDb()
	{
		$db = Zend_Db::factory('Pdo_Mssql', array(
			'host'     => DB_HOST,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'dbname'   => DB_NAME,
			'pdoType' => 'dblib'
		));
		Zend_Db_Table::setDefaultAdapter($db);        
	}
}

