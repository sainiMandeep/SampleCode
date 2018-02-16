<?php 
require_once('../../server-vars.php');

if (!defined('APP_ENV') || APP_ENV === 'PRODUCTION') {
	return;
}
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(dirname(__FILE__) . '/../../library'),
	get_include_path(),
	)));

require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();
Zend_Loader::loadClass('Zend_Db');
require_once('db.php');

//employee
if (!(isset($_GET['serial_number']))) {
	echo 'Provide a serial number';
	return;
}
$serial_number = $_GET['serial_number'];
if (!(isset($_GET['status']))) {
	echo 'Provide a status';
	return;
}
$status = $_GET['status'];
$recovery = $db->fetchOne($db->select()->from('recovery')
		->where('serial_number = ?',$serial_number)
);
if (!$recovery) {
	echo 'Wrong serial number<br>';	
	return;
}
$data = array(
	'status' => intval($status)
);
$where = $db->quoteInto('serial_number = ?', $serial_number);
try {
	$db->update('recovery',$data,$where);		
} catch (Exception $e) {
	echo 'Error during the update<br>';	
	return;
}
echo 'Operation done! ready to test<br>';