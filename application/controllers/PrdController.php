<?php
class PrdController extends Zend_Controller_Action
{
    public function init()
    {
    	if (!Application_Model_User::isVendor())
			$this->_redirect('/');
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
}