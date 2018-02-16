<?php
class IndexController extends Zend_Controller_Action
{

	public function init() {
		$this->_helper->layout->disableLayout();
	}
	
	public function indexAction() 
	{
		$form = new Application_Form_User_Signin();        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $user = new Application_Model_User();
                $auth = $user->signin($form->password->getValue());
                if (!$auth) {
                    $form->password->addError('Incorrect password');
                } else {
                    $_SESSION['admin'] = false;
                    if ($form->password->getValue() == 'ADMN') {
                        $_SESSION['admin'] = true;
                    }                        
                    $_SESSION['auth'] = true;
                    $_SESSION['firstname'] = $auth->firstname;
                    $_SESSION['lastname'] = $auth->lastname;
                    $_SESSION['employee_id'] = $auth->employee_id;
                    $_SESSION['vendor'] = $auth->vendor;
                    if ($_SESSION['vendor'] === 'MWS') {
                        $this->_redirect('/mws/scan');
                    }
                    $this->_redirect('/checkin/list');
                }
            }
        }
		$this->view->form = $form;
	}

	public function signoutAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		session_destroy();
		unset($_SESSION);
		$this->_redirect('/');
	}


	/**
	 * The index action handles index/list requests; it should respond with a
	 * list of the requested resources.
	 */
	public function scanAction()
	{  
		// // assert serial number is given
		if (!$serial_number =  $this->getRequest()->getParam('serial_number')) {
			$this->_forward('datamissing','error');
			return;
		}
        // assert serial number exist in recovery table
		$recovery = Application_Model_Recovery::findOne(array('serial_number' => $serial_number));
		if (!$recovery) {
        	$this->_forward('badserialnumber','error'); // need to be changed
        	return;
        }

        // check if user is vendor
        if (Application_Model_User::isVendor()) {
        	switch ($recovery->getStatus()) {
        		case 1: // Shipped
        		case 2: // Sent
        			if ($recovery->getRecoveryType() == 1) {
	    	    		$this->_forward('unusedmeds','checkin',NULL, array('serial_number' =>  $serial_number));
	    	    		return;
	    	    	}
	    	    	elseif ($recovery->getRecoveryType() == 2) {
	    	    		$this->_forward('awr','checkin',NULL, array('serial_number' =>  $serial_number));
	    	    		return;
	    	    	}
	        		break;
	       		case 3: // Checkin
	       			if ($recovery->getRecoveryType() == 1) {
        				$this->_forward('unusedmeds','process',NULL, array('serial_number' =>  $serial_number));
        				return;
        			}
        			elseif ($recovery->getRecoveryType() == 2) {
        				$this->_forward('awr','checkin',NULL, array('serial_number' =>  $serial_number));
        				return;
        			}
	        		break;
        		case 4: // Processed
        		case 6: // InBin
        		case 7: // InBin Partially rejected
        			if ($_SESSION['admin'])
	        			$this->_forward('revertprocess','process');
	        		else
	        			$this->_forward('itemprocessed','error');
        			return;
        		case 8: // Destroyed
					$this->_forward('itemprocessed','error');
					return;
				//Rejected
				case 5:
        			if ($_SESSION['admin'])
	        			$this->_forward('revertprocess','process');
	        		else
	        			$this->_forward('itemrejected','error');
					return;
				case 9: // On hold
	       			if ($recovery->getRecoveryType() == 1) {
        				$this->_forward('unusedmeds','checkin',NULL, array('serial_number' =>  $serial_number));
        				return;
        			}
	       			if ($recovery->getRecoveryType() == 2) {
        				$this->_forward('awr','checkin',NULL, array('serial_number' =>  $serial_number));
        				return;
        			}
        		default:
        			break;
        	}
			$this->_forward('error','error');
			return;
        }
        // else user is customer
        else {
        	switch ($recovery->getStatus()) {
	    		case 1: // Shipped
	    		case 2: // Sent
		    		$this->_forward('index','customer',NULL, array('serial_number' =>  $serial_number));
		    		return;
	    		break;
	    		case 4: // Processed
        		case 5: // Rejected
        		case 6: // InBin
        		case 7: // InBin Partially rejected
        		case 8: // Destroyed
	    		default:
	    			break;
	    	}
	    	$this->_forward('error','error');
	    	return;
	    }
	}

	/**
	 * 
	 * @return 
	 */
	public function addressAction() {
		$this->_helper->layout->setLayout('default');

		if (!$serial_number = $this->getRequest()->getParam('serial_number'))
		    return;

		$recovery = Application_Model_Recovery::findOne(array('serial_number' => $serial_number));
		if ($recovery) {
        	$this->_forward('index','index');
        	return;
        }
		$form = new Application_Form_Index_Address($serial_number);
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
		    if ($form->isValid($this->getRequest()->getPost())) {
		    	Ontraq_Mail::sendNotRecognizedItem(array(
                        'recovery_type' => Application_Model_Recovery::mapNameType($form->recovery_type->getValue()),
                        'serial_number' => $serial_number,
                        'street' =>  $form->street->getValue(),
                        'city' => $form->city->getValue(),
                        'zipcode' => $form->zipcode->getValue(),
                        'state' => $form->state->getValue()
                    ));
		    	$recoveryModel = new Application_Model_Recovery();
		    	$date = new Ontraq_Date();
		    	$date->setNow();
		    	if ($recoveryModel->create(array(
		    		'recovery_type' => $form->recovery_type->getValue(),
		    		'serial_number' => $serial_number,
		    		'customer_number' => 'PRISTINE',
		    		'status' => 1,
		    		'shipped_date' => $date->getSQL(),
		    		'updated_date' => $date->getSQL()
		    	))) {
		    		$this->_redirect('/index/scan/serial_number/'.$serial_number);
		    	}
		    	$this->view->success = true;
		    }
		}
		$this->view->serial_number = $serial_number;
	}

	public function createAction() {
		$this->_helper->layout->setLayout('default');

		if (!$serial_number = $this->getRequest()->getParam('serial_number'))
		    return;

		$recovery = Application_Model_Recovery::findOne(array('serial_number' => $serial_number));
		if ($recovery) {
        	$this->_forward('index','index');
        	return;
        }

		$form = new Application_Form_Index_Create($serial_number);
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
		    if ($form->isValid($this->getRequest()->getPost())) {
		    	$recoveryModel = new Application_Model_Recovery();
		    	$date = new Ontraq_Date();
		    	$date->setNow();

		    	if ($recoveryModel->create(array(
		    		'recovery_type' => $form->recovery_type->getValue(),
		    		'serial_number' => $serial_number,
		    		'customer_number' => 'PRISTINE',
		    		'status' => 1,
		    		'shipped_date' => $date->getSQL(),
		    		'updated_date' => $date->getSQL()
		    	))) {
		    		$this->_redirect('/index/scan/serial_number/'.$serial_number);
		    	}
		    	$this->view->error = true;
		    }
		    
		}
		$this->view->serial_number = $serial_number;
	}
}

