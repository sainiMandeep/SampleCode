<?php
class EmployeeControllerTest extends ControllerTestCase {
	
	
	protected $employee;
	
	public function setUp(){
		parent::setUp();
			
	}
	
 	public function testIndexAction() {
		$this->dispatch ( '/employee/index' );
		$this->assertController ( 'employee' );
		$this->assertAction ( 'index' );
		$this->assertResponseCode ( "200" );
	} 
	 
	/* public function deleteTestData(){
		$this->employee = Employee::delete();
	} */
		public function testcreate() {
		$data = array (
				"firstname" => "PHPUNIT2",
				"lastname" => "phpunit2" 
		);
		$this->request->setMethod ( 'POST' )->setPost ( $data );
		$this->dispatch ( "/employee/create" );
		$this->assertController ( "employee" );
		$this->assertAction ( "create" );
		$this->assertResponseCode ( "200" );
	}  
	 public function testerror(){
		$data = array (
				"firstname" => "",
				"lastname" => ""
		);
		$this->request->setMethod ( 'POST' )->setPost ( $data );
		$this->dispatch ( "/employee/create" );
		$this->assertController ( "employee" );
		$this->assertAction ( "create" );
	}
	public function testdelete(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$query = "SELECT employee_id from employee where firstname = 'PHPUNIT2'";
		$result = $db->query($query)->fetch();
		$id = $result['employee_id'];
		$this->dispatch ( "/employee/remove/token/".$id );
		$this->assertController ( "employee" );
		$this->assertAction ( "remove" );
		$this->assertResponseCode ( "200" );
		
	} 
	public function testDeleteWithnoToken(){			 
		$this->request->setMethod('GET')->setPost(array("token" => ''));
		$this->dispatch("/employee/remove/");
		$this->assertController ( "error" );
		$this->assertAction ( "error" );

	}
}