<?php
class EmployeeController extends Zend_Controller_Action {
	public function init() {
		if (!Application_Model_User::isVendor())
			$this->_redirect('/');
		
		/* Initialize action controller here */
		$this->view->current_page = 'employee';
		
	}
	public function indexAction() {
		if (!$_SESSION['admin']) {
			$this->_forward('unauthorized','error',NULL);
			return;
		}

		// action body
		if ($this->getRequest()->isXmlHttpRequest()) 
			$this->_helper->layout->disableLayout ();
		$list = Application_Model_Employee::findAll();
		$this->view->employee = $list;
	}
	public function createAction() {
		if (!$_SESSION['admin']) {
			$this->_forward('unauthorized','error',NULL);
			return;
		}

		$this->_helper->layout->disableLayout ();
		$request = $this->getRequest ();
		$form = new Application_Form_Employee_Create ();
		if ($this->getRequest ()->isPost ()) {
			if ($form->isValid ( $request->getPost () )) {
				$employee = new Application_Model_Employee ();
				$employee->create ( $form );
			} else {
				$this->view->error = true;
			}
		}
		$this->view->form = $form;
		
	}
	public function removeAction(){
		if (!$_SESSION['admin']) {
			$this->_forward('unauthorized','error',NULL);
			return;
		}

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);			
		if (!$key = $this->getRequest()->getParam('token')) {	
			$this->_forward('error','error');
		}		
		if (!($employee = Application_Model_Employee::findOne(array('employee_id' => $key)))) {
			echo json_encode(array('status'=>'error'));
			return;
		}
		$employee->remove();
		echo json_encode(array('status'=>'success'));
        return; 
	}	
}