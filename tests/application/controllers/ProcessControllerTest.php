<?php
class ProcessControllerTest extends ControllerTestCase
{
	protected $unusedMeds;
	
	public function setUp(){
		parent::setUp();
	}

	public function testVendorAccessCorrectPageWithWrongStatus()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 2, 'recovery_type' => 1));
		$this->dispatch("/process/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("checkin",'Serial number with incorrect status should access index');
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithNoSerialNumberForUnusedMedsPage()
	{
		$this->dispatch("/process/unusedmeds");
		$this->assertController("error");
		$this->assertAction("error");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithWrongSerialNumberForUnusedPage()
	{
		$this->dispatch("/process/unusedmeds/serial_number/XXX_XXX");
		$this->assertController("error");
		$this->assertAction("badserialnumber");
		$this->assertResponseCode("200");
	}

	public function testNotVendorAreRedirectedToErrorPage()
	{
		User::setCustomer();
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->dispatch("/process/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");
		$this->assertAction("auth");
		$this->assertResponseCode("200");
	}

	// /************ UNUSED MEDS *****************/


	public function testVendorCanAccessUnusedMedsWithCorrectSerialNumber()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->dispatch("/process/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("process");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testVendorAccessErrorPageWithWrongRecoveryType()
	{
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 2));
		$this->dispatch("/process/unusedmeds/serial_number/".$this->unusedMeds['serial_number']);
		$this->assertController("error");
		$this->assertAction("error");
		$this->assertResponseCode("200");
	}

	public function testSubmitIsWorking() {
		$this->employee = Employee::generate();
		$data = UnusedMeds::processForm(array('processed_user_code' => $this->employee['code']));
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/process/unusedmeds/serial_number/".$data['serial_number']);
		// echo $this->response->getBody();
		$unusedMed = Application_Model_UnusedMeds::findOne(array('serial_number' => $data['serial_number']));
		$this->assertNotEquals(FALSE, $unusedMed);
		$this->assertRedirect();
		$this->assertEquals($data['processed_date'], Ontraq_Date::sqlToJQUERY($unusedMed->getProcessedDate()),'process date should correspond');
		$this->assertEquals($data['checkin_notes'], $unusedMed->getCheckinNotes(),'process notes should correspond');
		$this->assertEquals($data['general_notes'], $unusedMed->getGeneralNotes(),'general notes should correspond');
		$this->assertEquals($this->employee['employee_id'], $unusedMed->getProcessedUserId(),'process user id should correspond');
		$this->assertEquals(4, $unusedMed->getStatus());

		$medications = Application_Model_Medication::findAll(array('recovery_id' => $unusedMed->recovery_id));
		$this->assertNotEquals(FALSE, $medications);
		$this->assertNotEquals(0, count($medications));
		$this->assertEquals(4, count($medications));

		$this->assertController("process");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("302");
	}

	public function testSubmitErrorValidationWithWrongDate() {
		$data = UnusedMeds::processForm(array('processed_user_code' => $this->employee['code']));
		unset($data['processed_date']);
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/process/unusedmeds/serial_number/".$data['serial_number']);
		$unusedMed = Application_Model_UnusedMeds::findOne(array('serial_number' => $data['serial_number']));
		$this->assertQueryCount('form.error',1);
		$this->assertEquals($this->unusedMeds['status'], $unusedMed->getStatus());
		$this->assertController("process");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testSubmitErrorValidationWithNoEmployeeCode() {
		$data = UnusedMeds::processForm();
		unset($data['processed_user_id']);
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/process/unusedmeds/serial_number/".$data['serial_number']);
		$this->assertQueryCount('form.error',1);
		$this->assertController("process");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}

	public function testSubmitErrorValidationWithWrongEmployeeCode() {
		$data = UnusedMeds::processForm();
		$data['processed_user_code'] = 'BBBB';
		$this->unusedMeds = UnusedMeds::generate(array('status' => 3, 'recovery_type' => 1));
		$this->request->setMethod('POST')->setPost($data);
		$this->dispatch("/process/unusedmeds/serial_number/".$data['serial_number']);
		$this->assertQueryCount('form.error',1);
		$this->assertController("process");
		$this->assertAction("unusedmeds");
		$this->assertResponseCode("200");
	}
}