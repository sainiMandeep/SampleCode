<?php
class Application_Model_Employee extends Zend_Db_Table_Abstract {
	protected $_name = 'employee';
	protected $_primary = 'employee_id';
	/**
	 * Get employee_id
	 *
	 * @return employee_id
	 */
	public function getEmployeeId() {
		if (isset ( $this->employee_id ))
			return $this->employee_id;
		return false;
	}
	
	/**
	 * Set employee_id
	 *
	 * @param
	 *        	employee_id
	 */
	public function setEmployeeId($employee_id) {
		$this->employee_id = $employee_id;
	}
	/**
	 * Get last_name
	 *
	 * @return last_name
	 */
	/**
	 * Get first_name
	 *
	 * @return first_name
	 */
	public function getFirstName() {
		if (isset ( $this->firstname ))
			return $this->firstname;
		return false;
	}
	
	/**
	 * Set first_name
	 *
	 * @param
	 *        	first_name
	 */
	public function setFirstName($first_name) {
		$this->firstname = $first_name;
	}
	/**
	 * Get last_name
	 *
	 * @return last_name
	 */
	public function getLastName() {
		if (isset ( $this->lastname ))
			return $this->lastname;
		return false;
	}
	
	/**
	 * Set lastname
	 *
	 * @param
	 *        	lastname
	 */
	public function setLastName($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * Get code
	 *
	 * @param
	 *        	code
	 */
	public function getCode() {
		if (isset ( $this->code ))
			return $this->code;
		return false;
	}
	
	/**
	 * Set code
	 *
	 * @param
	 *        	code
	 */
	public function setCode($code) {
		$this->code = $code;
	}
	
	/**
	 * Get created_date
	 *
	 * @param
	 *        	created_date
	 */
	public function getCreatedDate() {
		if (isset ( $this->created_date ))
			return $this->created_date;
		return false;
	}
	
	/**
	 * Set created_date
	 *
	 * @param
	 *        	created_date
	 */
	public function setCreatedDate($created_date) {
		$this->created_date = $created_date;
	}
	
	/* create employee in database */
	public function create($form) {
		$generated_code = $this->generateCode ()->fetch ();
		$key = $this->insert ( array (
				'code' => $generated_code ['code'],
				'firstname' => $form->firstname->getValue (),
				'lastname' => $form->lastname->getValue (),
				'active' => 1 
		) );
		if ($key) {
			return $key;
		} else {
			return false;
		}
	}
	public function generateCode() {
		return $this->getDefaultAdapter ()->query ( 'SELECT LEFT(newid(), 4) as code' );
	}
	public static function findAll($params = array()) {
		if (!$params || !isset($params['active'])) {
			$params['active'] = 1;
		}
		$employee = new Application_Model_Employee();
		$select = $employee->select();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value);
			}
		}
		$select->order('firstname');
		if ($rows = $employee->fetchAll($select)) {
			$employees = $rows->toArray ();
			foreach ( $employees as $key => $employee ) {
				$employees [$key] ['emp_name'] = ucfirst ( $employee ['firstname'] . ' ' . $employee ['lastname'] );
			}
		}
		return $employees;
	}


	/**
	 * query database to get one employee. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_Employee
	 */
	public static function findOne($params = array()) {
		$employee = new Application_Model_Employee();
		$select = $employee->select();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value);
			}
		}
		if (!is_array($params)) {
			$select->where('employee_id = ?', $Params);
		}

		if ($row = $employee->fetchRow($select)) {
			$employee->setEmployeeId($row->employee_id);
			$employee->setFirstName($row->firstname);
			$employee->setLastName($row->lastname);
			$employee->setCode($row->code);
			$employee->setCreatedDate($row->created_date);
			return $employee;
		}
		return false;
	}
	
	
	public function remove() {
		$key = $this->getEmployeeId();		
		if (! $key) {
			throw new Zend_Exception ( "Params are required", 1 );
		}
		$data = array (
				'active' => 0 
		);
		$where = $this->getAdapter ()->quoteInto ( 'employee_id = ?', $key );
		return parent::update ( $data, $where );
	}	
}