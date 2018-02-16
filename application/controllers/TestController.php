<?php

class TestController extends Zend_Controller_Action
{

	public function init() {
        if (!Application_Model_User::isVendor())
            $this->_redirect('/');
        $this->_helper->viewRenderer->setNoRender(true);
	}

    public function checkinAction() {

    	$customer_number = 'TST9999';
    	$UnusedMeds = new Application_Model_UnusedMeds();
    	$where = $UnusedMeds->getAdapter()->quoteInto('customer_number = ?',$customer_number);
    	$UnusedMeds->delete($where);

    	$data = array(
    	    'recovery_type'      => 1,
    	    'serial_number' => 'DEVTEST',
    	    'status'      => 1,
    	    'shipped_date'      => '11-08-2013',
    	    'customer_number'      => 'TST9999',
    	    'updated_date'      => Ontraq_Date::now('SQL')
    	);
        if ($UnusedMeds->insert($data)) {
        	echo "1 Checkin unused created";
        }
    }

    public function processAction() {
        $customer_number = 'TST9999';
        $UnusedMeds = new Application_Model_UnusedMeds();
        $where = $UnusedMeds->getAdapter()->quoteInto('customer_number = ?',$customer_number);
        $UnusedMeds->delete($where);
        $data = array(
            'recovery_type'      => 1,
            'serial_number' => 'DEVTEST',
            'status'      => 3,
            'shipped_date'      => '11-08-2013',
            'customer_number'      => 'TST9999',
            'updated_date'      => Ontraq_Date::now('SQL')
        );
        if ($UnusedMeds->insert($data)) {
            echo "1 Processed unused created";
        }
    }
}

