<?php
class Application_Model_UnusedmedsTest extends ControllerTestCase
{
	protected $unusedMeds;
	public function setUp(){
		// $this->unusedMeds = UnusedMeds::generate();
		parent::setUp();
	}

	public function testFindOneReturnOneRow(){
		// $recovery = Application_Model_Recovery::findOne(array('serial_number' => $this->unusedMeds['serial_number']));
		// $this->assertEquals($this->unusedMeds['serial_number'], trim($recovery->getSerialNumber()),'findOne should return correct serial_number');
	}

	public function testFindOneReturnFalseWhenWrongSerialNumber(){
		// $recovery = Application_Model_Recovery::findOne(array('serial_number' => 'XX__XX__XX'), 'findOne should return false');
		// $this->assertEquals(false, $recovery);
	}

	public function testUpdate(){
	}
}