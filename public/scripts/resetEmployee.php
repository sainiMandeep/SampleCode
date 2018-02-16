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
$firstname = 'tester';
$lastname = 'test';
$employee_id = $db->fetchOne($db->select()->from('employee')
		->where('firstname = ?',$firstname)
		->where('lastname = ?',$lastname)
);
if ($employee_id) {
	$n = $db->delete('employee', 'employee_id = '.$employee_id);
	echo 'Employee '.$firstname.' '.$lastname.' deleted<br>';
}
echo 'Operation done! ready to test<br>';