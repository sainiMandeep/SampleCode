<?php
class BinControllerTest extends ControllerTestCase {
	
	
	protected $bin;
	
	public function setUp(){
		parent::setUp();
			
	}
	
 	public function testIndexAction() {
		$this->dispatch ( '/bin/index' );
		$this->assertController ( 'bin' );
		$this->assertAction ( 'index' );
		$this->assertResponseCode ( "200" );
	} 
	 
	/* public function deleteTestData(){
		$this->employee = Employee::delete();
	} */
		public function testcreate() {
		$data = array (
				'waste_type' => '2',
				'bin_type' => '1',
				'binlocation' => 'unit_test',
				'number_id' => 'Phpunitid1'  
		);
		$this->request->setMethod ( 'POST' )->setPost ( $data );
		$this->dispatch ( "/bin/create" );
		$this->assertController ( "bin" );
		$this->assertAction ( "create" );
		$this->assertResponseCode ( "200" );
	}  
	  public function testerror(){
		$data = array (
				'waste_type' => '',
				'bin_type' => '',
				'binlocation' => '',
				'number_id' => ''  
		);
		$this->request->setMethod ( 'POST' )->setPost ( $data );
		$this->dispatch ( "/bin/create" );
		$this->assertController ( "bin" );
		$this->assertAction ( "create" );
	}
	/*public function testdelete(){
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

	} */
	
}