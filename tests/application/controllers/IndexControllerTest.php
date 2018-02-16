<?php
class IndexControllerTest extends ControllerTestCase
{

	protected $unusedMeds;
	
	public function setUp(){
		parent::setUp();
	}

	/************ TEST FOR VENDORS *****************/

	public function testVendorScanIsRedirectedWhenNoSerialNumber()
	{
		$this->dispatch("/index/scan");
		$this->assertController("error");;
		$this->assertAction("datamissing");
		$this->assertResponseCode("200");
	}

	public function testVendorScanIsRedirectedWhenInvalidSerialNumber()
	{
		$this->dispatch("/index/scan/serial_number/XXX__XX");
		$this->assertController("error");;
		$this->assertAction("badserialnumber");
		$this->assertResponseCode("200");
	}

	/************ TEST FOR VENDORS : UnusedMeds *****************/

	public function testVendorScanAccessUnusedMedsCheckinWhenStatus1()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("checkin");;
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorScanAccessUnusedMedsCheckinWhenStatus2()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 2, 'recovery_type' => 1));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("checkin");;
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorScanAccessUnusedMedsProcessWhenStatus3()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("process");;
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	/************ TEST FOR VENDORS : Error *****************/

	public function testVendorScanAccessErrorWithSerialNumberInStatus4()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 4));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");;
		$this->assertAction("itemprocessed");
		$this->assertResponseCode("200");
	}

	public function testVendorScanAccessErrorWithSerialNumberInStatus5()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 5));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");;
		$this->assertAction("itemprocessed");
		$this->assertResponseCode("200");
	}


	public function testVendorScanAccessErrorWithSerialNumberInStatus6()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 6));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");;
		$this->assertAction("itemprocessed");
		$this->assertResponseCode("200");
	}

	public function testVendorScanAccessErrorWithSerialNumberInStatus7()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 7));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");;
		$this->assertAction("itemprocessed");
		$this->assertResponseCode("200");
	}

	/************ TEST FOR CUSTOMER *****************/

	public function testCustomerScanAccessCustomerPortalWithSerialNumberInStatus1()
	{
		User::setCustomer();
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("customer");;
		$this->assertAction("index");
		$this->assertResponseCode("200");
	}

	public function testCustomerScanAccessCustomerPortalWithSerialNumberInStatus2()
	{
		User::setCustomer();
		$this->unusedMeds = UnusedMeds::generate(array('status' => 2));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("customer");;
		$this->assertAction("index");
		$this->assertResponseCode("200");
	}

	public function testCustomerScanAccessErrorWithSerialNumberInStatus3()
	{
		User::setCustomer();
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3));
		$this->dispatch("/index/scan/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");;
		$this->assertAction("error");
		$this->assertResponseCode("200");
	}
}