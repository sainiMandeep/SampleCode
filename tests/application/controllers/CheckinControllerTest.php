<?php
class CheckinControllerTest extends ControllerTestCase
{
	protected $unusedMeds;
	
	public function setUp(){
		parent::setUp();
	}

	public function testVendorAccessErrorPageWithWrongStatus()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->dispatch("/checkin/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("process",'Serial number with incorrect status should access index');
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithNoSerialNumberForUnusedMedsPage()
	{
		$this->dispatch("/checkin/unusedmeds");
		$this->assertController("error");
		$this->assertAction("error");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithWrongSerialNumberForUnusedPage()
	{
		$this->dispatch("/checkin/unusedmeds/serial_number/XXX_XXX");
		$this->assertController("error");
		$this->assertAction("badserialnumber");
		$this->assertResponseCode("200");
	}

	public function testNotVendorAreRedirectedToErrorPage()
	{
		User::setCustomer();
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->dispatch("/checkin/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");
		$this->assertAction("auth");
		$this->assertResponseCode("200");
	}

	// /************ UNUSED MEDS *****************/


	public function testVendorCanAccessUnusedMedsWithCorrectSerialNumber()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->dispatch("/checkin/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("checkin");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithWrongRecoveryType()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 2));
		$this->dispatch("/checkin/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");
		$this->assertAction("error");
		$this->assertResponseCode("200");
	}

	public function testSubmitIsWorking() {
		$this->employee = Employee::generate();
		$data = UnusedMeds::checkinForm(array('checkin_user_code' => $this->employee['code']));
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/checkin/unusedmeds/serial_number/".$data['serial_number']);
		$unusedMed = Application_Model_UnusedMeds::findOne(array('serial_number' => $data['serial_number']));
		$this->assertNotEquals(FALSE, $unusedMed);
		$this->assertRedirect();
		$this->assertEquals($data['weight'], $unusedMed->getWeight(),'weight should correspond');
		$this->assertEquals($data['checkin_date'], Ontraq_Date::sqlToJQUERY($unusedMed->getCheckinDate()),'checkin date should correspond');
		$this->assertEquals($data['checkin_notes'], $unusedMed->getCheckinNotes(),'checkin notes should correspond');
		$this->assertEquals($data['general_notes'], $unusedMed->getGeneralNotes(),'general notes should correspond');
		$this->assertEquals($this->employee['employee_id'], $unusedMed->getCheckinUserId(),'checkin user id should correspond');
		$this->assertEquals(3, $unusedMed->getStatus());
		$this->assertController("checkin");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("302");
	}

	public function testSubmitErrorValidationWithWrongDate() {
		$data = UnusedMeds::checkinForm(array('checkin_user_code' => $this->employee['code']));
		unset($data['checkin_date']);
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/checkin/unusedmeds/serial_number/".$data['serial_number']);
		$unusedMed = Application_Model_UnusedMeds::findOne(array('serial_number' => $data['serial_number']));
		$this->assertQueryCount('form.error',1);
		$this->assertEquals($this->unusedMeds['status'], $unusedMed->getStatus());
		$this->assertController("checkin");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testSubmitErrorValidationWithNoEmployeeCode() {
		$data = UnusedMeds::checkinForm();
		unset($data['checkin_user_id']);
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/checkin/unusedmeds/serial_number/".$data['serial_number']);
		$this->assertQueryCount('form.error',1);
		$this->assertController("checkin");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testSubmitErrorValidationWithWrongEmployeeCode() {
		$data = UnusedMeds::checkinForm();
		$data['checkin_user_code'] = 'BBBB';
		$this->unusedMeds = UnusedMeds::generate(array('status' => 1, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/checkin/unusedmeds/serial_number/".$data['serial_number']);
		$this->assertQueryCount('form.error',1);
		$this->assertController("checkin");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}
}