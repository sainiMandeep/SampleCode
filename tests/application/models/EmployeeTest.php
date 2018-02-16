<?php
class Application_Model_EmployeeTest extends ControllerTestCase{
	protected $employee;
	public function setUp() {
		parent::setUp ();
		$this->employee = new Application_Model_Employee ();
	}
	public function testcanGetEmployees() {
		$employeesArray = new Application_Model_Employee ();
		$employees = $employeesArray->findAll ();
		$this->assertTrue ( is_array ( $employees ) );
	}
	
} 

