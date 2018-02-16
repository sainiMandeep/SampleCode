<?php
class Application_Model_User
{
	public static function isVendor() {

		if (isset($_SESSION['auth']) && $_SESSION['auth']) {
			return true;
		}
		return false;
	}



	public function signin($password) {
		$auth = Zend_Auth::getInstance();
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$authAdapter = new Zend_Auth_Adapter_DbTable(
		    $dbAdapter,
		    'employee',
		    'code',
		    'code'
		);
		$authAdapter
		    ->setIdentity($password)
		    ->setCredential($password);
		 try {
		 	$result = $auth->authenticate($authAdapter);
		 } catch (Exception $e) {
		 	echo $e->getMessage();
		 }
	   
	   
	    if (!$result->isValid())
	    	return false;
		return $authAdapter->getResultRowObject();
	}

}