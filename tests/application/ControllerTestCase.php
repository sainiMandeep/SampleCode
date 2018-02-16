<?php
require_once(APPLICATION_PATH.'/../tests/application/data/UnusedMeds.php');
require_once(APPLICATION_PATH.'/../tests/application/data/User.php');
require_once(APPLICATION_PATH.'/../tests/application/data/Employee.php');
require_once(APPLICATION_PATH.'/../tests/application/data/MedicationFavorite.php');
class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
	/**
	 * Zend application object
	 * @var unknown_type
	 */
	protected $application;
	
	public function setUp()
	{
		$this->bootstrap = array($this, 'appBootstrap');
		parent::setUp();
	}
	
	public function appBootstrap()
	{
		$this->application = new Zend_Application(APP_ENV, APPLICATION_PATH.'/configs/application.ini');
		$this->application->bootstrap();
		$front = Zend_Controller_Front::getInstance();
		if($front->getParam('bootstrap') === null) {
			$front->setParam('bootstrap', $this->application->getBootstrap());
		} 
	}
}