<?php

class NdcController extends Zend_Controller_Action
{

	public function init()
	{
        if (!Application_Model_User::isVendor())
            $this->_redirect('/');
		$this->_helper->layout->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
	}

    public function medicationsAction() {
        if (!$search =  $this->getRequest()->getParam('search'))
            $search = false;
        $medication = Application_Model_MedicationNDC::findAll($search);
        if ($medication) {
            echo json_encode($medication);
            return;
        }

        echo json_encode(array());
    }
}


