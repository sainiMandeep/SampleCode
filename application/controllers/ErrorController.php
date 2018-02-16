<?php

class ErrorController extends Zend_Controller_Action
{
    public function init() {
        if (!Application_Model_User::isVendor())
            $this->_redirect('/');
        $this->_helper->layout->setLayout('default');
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');


        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';

		// TEMPORARY
                $body = 'Message: '.$errors->exception->getMessage().'<br>';
                $body.= 'Stack trace: '.$errors->exception->getTraceAsString().'<br>';
                $body.= 'Request Parameters: '.var_export($errors->request->getParams(), true).'<br>';
                Ontraq_Mail::sendMail(array(
                    'to' => DEV_EMAIL,
                    'subject' => 'RECOVERY PORTAL ERROR',
                    'body' => $body
                    )
                );
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function datamissingAction() {
    }

    public function badserialnumberAction() {
         if (!$serial_number = $this->getRequest()->getParam('serial_number'))
            return;
        $this->view->serial_number = $serial_number;
    }

    public function itemprocessedAction() {
    }

    public function itemrejectedAction() {
    }

    public function unauthorizedAction() {
    }

    public function authAction() {
        $this->_helper->layout->disableLayout();
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
