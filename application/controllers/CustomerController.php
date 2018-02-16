<?php

class CustomerController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
		if (!Application_Model_User::isVendor())
			$this->_redirect('/');
	}

	public function indexAction()
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
		$recovery = Application_Model_Recovery::findOne(array('serial_number' => $serial_number));
		if (!$recovery) {
			$this->_forward('badserialnumber','error'); // need to be changed
			return;
		}

		// Get the customer associated
	    if ($customer = Application_Model_Customer::findOne($recovery->getCustomerNumber())) {	
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
	     	$this->view->customer = $customer;
	    }
	}
}

