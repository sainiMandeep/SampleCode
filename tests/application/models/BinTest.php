<?php
class Application_Model_BinTest extends ControllerTestCase{
	protected $bin;
	
	public function setUp() {
		parent::setUp ();
		$this->bin = new Application_Model_Bin();
		
	}
	
	public function testCanCreateBinForm()
	{
		$form = array(
				'waste_type' => '2',
				'bin_type' => '1',
				'binlocation' => 'unit_test',
				'number_id' => 'Phpunitid1'
		);
		$key =$this->bin->create($form);
		$this->assertTrue(isset($key));
	}
	public function testFindAllLocation(){
		$modellocation =  Application_Model_Location::findAll();
		$this->assertTrue(is_array($modellocation));
		
	}
	public function testFindOne(){
		$modellocation =  Application_Model_Location::findOne(array (
					'name' => 'office' ));
		$this->assertTrue(is_array($modellocation));
	}
} 

