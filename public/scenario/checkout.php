<?php

require_once('../../server-vars.php');

set_include_path(implode(PATH_SEPARATOR, array(
realpath(dirname(__FILE__) . '/../../library'),
get_include_path(),
)));

require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();
Zend_Loader::loadClass('Zend_Db');

// practice
// $drdon = $db->fetchOne($db->select()->from('users')->where('email = ?',''));
// if ($drdon) {
// 	$db->query('exec deleteuser "qa.test@HealthFirst.com"');
// 	echo 'Existing account deleted<br>';
// }

// $practice = array(
// 	'loc_id' => $loc_id,
// 	'customer_number' => $customer_number
// );
// $result = $db->insert('practice',$practice);
// if (!$result)
// 	throw new Exception("Error", 1);

// $practice_id = $db->fetchOne($db->select()->from('practice',array('practice_id'))->limit(1)->order('practice_id DESC'));

// echo 'Practice created with customer number :'.$customer_number.'<br>';

?>

HELLO