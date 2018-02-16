<?php
$db = Zend_Db::factory('Pdo_Mssql', array(
	'host'     => DB_HOST,
	'username' => DB_USER,
	'password' => DB_PASSWORD,
	'dbname'   => DB_NAME,
	'pdoType' => 'dblib'
));
?>