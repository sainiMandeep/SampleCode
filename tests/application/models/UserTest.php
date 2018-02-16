<?php
class Application_Model_UserTest extends ControllerTestCase
{
	public function setUp(){
		parent::setUp();
	}

	public function testisVendor(){
		$this->assertEquals(true, Application_Model_User::isVendor(),'As PHP Unit run on 127.0.0.1 should return true');
	}
}