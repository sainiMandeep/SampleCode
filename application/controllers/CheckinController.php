<?php

class CheckinController extends Zend_Controller_Action
{
	public function init()
	{
		if (!Application_Model_User::isVendor())
			$this->_redirect('/');
		// $this->_helper->layout->disableLayout();
		$this->_helper->layout->setLayout('default');
		$this->view->no_breadcrumb = true;
	}

	public function listAction() {
		if (!Application_Model_User::isVendor()) {
			$this->_forward('auth','error');
			return;
		}
		$this->view->recoveries = Application_Model_Recovery::findALL(array('status' => 3), 100,'checkin_date desc');
		$this->view->current_page = array('menu' => 'checkin', 'label' => 'Checkin Items', 'subtitle' => 'Items checked-in');
	}

	public function onholdAction() {
		if (!Application_Model_User::isVendor()) {
			$this->_forward('auth','error');
			return;
		}
		$this->view->recoveries = Application_Model_Recovery::findALL(array('status' => 9), 100,'checkin_date desc');
		$this->view->current_page = array('menu' => 'onhold', 'label' => 'On hold items', 'subtitle' => 'Items on hold');
		$this->render('list');
	}

	public function rejectAction() {
		if (!Application_Model_User::isVendor()) {
			$this->_forward('auth','error');
			return;
		}
		$this->view->recoveries = Application_Model_Recovery::findALL(array('status' => 5), 100,'checkin_date desc');
		$this->view->current_page = array('menu' => 'reject', 'label' => 'Rejected items', 'subtitle' => 'Rejected items');
		$this->render('list');
	}

	public function unusedmedsAction()
	{
		if (!Application_Model_User::isVendor()) {
			$this->_forward('auth','error');
			return;
		}

		// assert serial number is given
		if (!$serial_number =  $this->getRequest()->getParam('serial_number')) {
			$this->_forward('error','error');
			return;
		}	
        // assert serial number exist in recovery table and status == 1 or 2 or 9
		$unusedMeds = Application_Model_UnusedMeds::findOne(array('serial_number' => $serial_number));
		if (!$unusedMeds || (($unusedMeds->getStatus() != 1) && ($unusedMeds->getStatus() != 2) && ($unusedMeds->getStatus() != 9))) {
	     	$this->_forward('scan','index',NULL, array('serial_number' =>  $serial_number)); // need tan(arg)o be changed
	     	return;
	     }		
	   
        // Get the customer associated
	     if ($customer = Application_Model_Customer::findOne($unusedMeds->getCustomerNumber())){   	   
	     	if ($customer['ExpirationDate'])  {
	     		$today_date =  date('m/d/Y');    	
	     		$today=date("Y-m-d H:i:s",strtotime($today_date));
	     		$expiryDate = $customer['ExpirationDate'];
	     		$expirationDate =  date("Y-m-d H:i:s",strtotime($expiryDate));	     
	     		if($today > $expirationDate){
	     			$customer['status']='expired';	     		
	     		}
	     		else{
	     			$customer['status']='current';
	     		}
	     	}
	     	else {
	     		$customer['status']='unknown';
	     	}
	     	$this->view->customer = json_encode($customer);
	    }

	     $form = new Application_Form_Checkin_UnusedMeds($serial_number);
	     if ($this->getRequest()->isPost()) {
	     	if ($form->isValid($this->getRequest()->getPost())) {
	     		$data = $form->mapModel();
	     		$data['checkin_user_id']= $_SESSION['employee_id'];
	     		if ($this->getRequest()->getParam('reject')) {
	     			$data['status'] = 5;	     			
	     			$unusedMeds->edit($data);
	     			$body = Ontraq_Mail::sendRejected(array(
	     				'type' => 1,
	     				'customer_number' => $unusedMeds->getCustomerNumber(),	     				
	     				'process' => 'checkin process',
	     				'serial_number' =>  $serial_number,
	     				'notes' => $form->general_notes->getValue()
	     				));
	     			$this->_redirect('checkin/reject');
	     		}
	     		elseif ($this->getRequest()->getParam('onhold')) {
	     			$data['status'] = 9;
	     			$unusedMeds->edit($data);
	     			if ($this->getRequest()->getParam('sendnotes')) {
	     				$body = Ontraq_Mail::sendGeneralNotes(array(
	     					'customer_number' => $unusedMeds->getCustomerNumber(),
	     					'process' => 'checkin process',
	     					'serial_number' =>  $serial_number,
	     					'notes' => $form->general_notes->getValue()
	     					));
	     			}
	     			$this->_redirect('checkin/onhold');
	     		}
	     		else {
	     			$data['status'] = 3;
	     			$unusedMeds->edit($data);
	     			if ($this->getRequest()->getParam('sendnotes')) {
	     				$body = Ontraq_Mail::sendGeneralNotes(array(
	     					'customer_number' => $unusedMeds->getCustomerNumber(),
	     					'process' => 'checkin process',
	     					'serial_number' =>  $serial_number,
	     					'notes' => $form->general_notes->getValue()
	     					));
	     			}
	     			$this->_redirect('process/unusedmeds/serial_number/'.$serial_number);
	     		}
	     	}
	     }
	     else {
	     	if ($unusedMeds->getStatus() == 9) {
	     		$form->populate(array(
	     			'weight' => $unusedMeds->getWeight(),
	     			'checkin_date' => Ontraq_Date::sqlToJQUERY($unusedMeds->getCheckinDate()),
	     			'checkin_notes' => $unusedMeds->getCheckinNotes(),
	     			'general_notes' => $unusedMeds->getGeneralNotes(),
	     			'onhold' => true
	     			));
	     	}
	     }

	     $this->view->current_page = array('menu' => 'checkin', 'label' => 'Checkin Unused Medications Bag', 'subtitle' => 'ID Number : '.$serial_number);
	     $this->view->serial_number = $serial_number;
	     $this->view->form = $form;
	 }

	 	public function awrAction()
	 	{
	 		if (!Application_Model_User::isVendor()) {
	 			$this->_forward('auth','error');
	 			return;
	 		}

	 		// assert serial number is given
	 		if (!$serial_number =  $this->getRequest()->getParam('serial_number')) {
	 			$this->_forward('error','error');
	 			return;
	 		}
	 		
	         // assert serial number exist in recovery table and status == 1 or 2 or 9
	 		$awr = Application_Model_Awr::findOne(array('serial_number' => $serial_number));
	 		if (!$awr || (($awr->getStatus() != 1) && ($awr->getStatus() != 2) && ($awr->getStatus() != 9))) {
	 	     	$this->_forward('scan','index',NULL, array('serial_number' =>  $serial_number)); // need tan(arg)o be changed
	 	     	return;
	 	     }

 	        // assert that alls bins are opens
 	     	$bin = Application_Model_Bin::findOne (array('onlyActive' => true, 'is_default' => true, 'bin.waste_type_id' => 8));
 	     	if (!$bin)
 	     		$this->_redirect('/bin');
 	     	$this->view->binNumberId = $bin->getNumberId();
 	     	$this->view->binTypeName = $bin->getBinTypeName();

	         // Get the customer associated
	 	     if ($customer = Application_Model_Customer::findOne($awr->getCustomerNumber())) {	
	 	     	if ($customer['ExpirationDate'])  {
	 	     		$today_date =  date('m/d/Y');    	
	 	     		$today=date("Y-m-d H:i:s",strtotime($today_date));
	 	     		$expiryDate = $customer['ExpirationDate'];
	 	     		$expirationDate =  date("Y-m-d H:i:s",strtotime($expiryDate));	     
	 	     		if($today > $expirationDate){
	 	     			$customer['status']='expired';	     		
	 	     		}
	 	     		else{
	 	     			$customer['status']='current';
	 	     		}
	 	     	}
	 	     	else {
	 	     		$customer['status']='unknown';
	 	     	}
	 	     	$this->view->customer = json_encode($customer);
	 	     }

	 	     $form = new Application_Form_Checkin_Awr($serial_number);
	 	     if ($this->getRequest()->isPost()) {
	 	     	if ($form->isValid($this->getRequest()->getPost())) {
	 	     		$data = $form->mapModel();
	 	     		$data['checkin_user_id']= $_SESSION['employee_id'];
	 	     		if ($this->getRequest()->getParam('reject')) {
	 	     			$data['status'] = 5;	     			
	 	     			$awr->edit($data);
	 	     			$body = Ontraq_Mail::sendRejected(array(
	 	     				'type' => 2,
	 	     				'customer_number' => $awr->getCustomerNumber(),
	 	     				'process' => 'checkin process',
	 	     				'serial_number' =>  $serial_number,
	 	     				'notes' => $form->general_notes->getValue()
	 	     				));
	 	     			$this->_redirect('checkin/reject');
	 	     		}
	 	     		elseif ($this->getRequest()->getParam('onhold')) {
	 	     			$data['status'] = 9;
	 	     			$awr->edit($data);
	 	     			if ($this->getRequest()->getParam('sendnotes')) {
	 	     				$body = Ontraq_Mail::sendGeneralNotesAWR(array(
	 	     					'customer_number' => $awr->getCustomerNumber(),
	 	     					'process' => 'checkin process',
	 	     					'weight' => $form->weight->getValue(),
	 	     					'employee' => $_SESSION['firstname'].' '.$_SESSION['lastname'],
	 	     					'serial_number' =>  $serial_number,
	 	     					'notes' => $form->general_notes->getValue()
	 	     					));
	 	     			}
	 	     			$this->_redirect('checkin/onhold');
	 	     		}
	 	     		else {
	 	     			$data['status'] = 4;
	 	     			$awr->edit($data);
	 	     			if ($this->getRequest()->getParam('sendnotes')) {
	 	     				$body = Ontraq_Mail::sendGeneralNotesAWR(array(
	 	     					'customer_number' => $awr->getCustomerNumber(),
	 	     					'process' => 'process',
	 	     					'weight' => $form->weight->getValue(),
	 	     					'employee' => $_SESSION['firstname'].' '.$_SESSION['lastname'],
	 	     					'serial_number' =>  $serial_number,
	 	     					'notes' => $form->general_notes->getValue()
	 	     					));
	 	     			}
	 	     			
	 	     			$this->_redirect('process/list');
	 	     		}
	 	     	}
	 	     }
	 	     else {
	 	     	$data = array();
	 	     	if ($awr->getWeight())
	 	     		$data['weight'] = $awr->getWeight();
	 	     	if ($awr->getWeight())
	 	     		$data['checkin_date'] = Ontraq_Date::sqlToJQUERY($awr->getCheckinDate());
	 	     	if ($awr->getWeight())
	 	     		$data['checkin_notes'] = $awr->getCheckinNotes();
	 	     	if ($awr->getWeight())
	 	     		$data['general_notes'] = $awr->getGeneralNotes();
	 	     	if ($awr->getStatus() == 9)
	 	     		$data['onhold'] = true;
	 	     	if (count($data)) {
	 	     		$form->populate($data);
	 	     	}
	 	     }

	 	     $this->view->current_page = array('menu' => 'checkin', 'label' => 'Checkin AWR', 'subtitle' => 'ID Number : '.$serial_number);
	 	     $this->view->serial_number = $serial_number;
	 	     $this->view->form = $form;
	 	 }
	}