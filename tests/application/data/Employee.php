<?php 
class Employee {
	public static function generate($params = null) {
		// Default
		$code = 'UNIT';
		$firstname = 'firstunit';
		$lastname = 'lastunit';
		if ($params) {
			if (isset($params['code']))
				$code = $params['code'];
			if (isset($params['firstname']))
				$firstname = $params['firstname'];
			if (isset($params['lastname']))
				$lastname = $params['lastname'];
		}
		$UnusedMeds = new Application_Model_UnusedMeds();
		$employee = new Application_Model_Employee();
		$where = $employee->getAdapter()->quoteInto('code = ?',$code);
		$employee->delete($where);
		$data = array(
			'code' => $code,
			'firstname' => $firstname,
			'lastname'  => $lastname,
			);
		if ($employee_id = $employee->insert($data)) {
			$data['employee_id'] = $employee_id;
			return $data;
		}
	}

	public static function delete() {
		$name = 'PHPUNIT2';
		$employees = new Application_Model_Employee();
		$where = $employees->getAdapter()->quoteInto('firstname = ?',$name);		
		$employees->delete($where);
	}
}