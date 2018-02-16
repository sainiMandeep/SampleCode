<?php

class ProcessController extends Zend_Controller_Action
{

	public function init() {
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
        $this->view->recoveries = Application_Model_Recovery::findALL(array('status' => 4), 100,'processed_date desc');
        $this->view->current_page = array('menu' => 'process', 'label' => 'Processed Items', 'subtitle' => 'Items processed');
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
        $this->serial_number = $serial_number;

        // assert serial number exist in recovery table and status == 1 or 2
        $unusedMeds = Application_Model_UnusedMeds::findOne(array('serial_number' => $serial_number));
        if (!$unusedMeds || ($unusedMeds->getStatus() != 3) || ($unusedMeds->getRecoveryType() != 1)) {
            $this->_forward('scan','index',NULL, array('serial_number' =>  $serial_number)); // need tan(arg)o be changed
            return;
        }

        $bins = Application_Model_Bin::findAll (array('onlyActive' => true, 'is_default' => true));
        $wasteTypes = Application_Model_WasteType::findAll();
        if ($missingBins = Application_Model_Bin::missing($bins, $wasteTypes)) {
            $this->_redirect('/bin');
            return;
        }
        if ($wasteTypes) {
            $wasteTypesJSON = array();
            foreach ($wasteTypes as $key => $value) {
                $wasteTypesJSON[] = $value['name'];
            }
            $this->view->wasteTypes = json_encode($wasteTypesJSON);
        }

        $favorites = Application_Model_MedicationFavorite::findAll();
        if ($favorites) {
            $this->view->favorites = json_encode($favorites);
        }

        if ($bins) {
            $binsJSON = array();
            foreach ($bins as $key => $value) {
                $binsJSON[$key]['bin_id'] = $value['bin_id'];
                $binsJSON[$key]['waste_type_id'] = $value['waste_type_id'];
                $binsJSON[$key]['category'] = $value['waste_type_name'];
                $binsJSON[$key]['number_id'] = $value['number_id'];
            }
            $this->view->bins = json_encode($binsJSON);
        }

        $form = new Application_Form_Process_UnusedMeds($serial_number);
        $form->checkin_notes->setValue($unusedMeds->getCheckinNotes());
        $form->general_notes->setValue($unusedMeds->getGeneralNotes());
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $data = $form->mapModel();
                $data['processed_user_id']= $_SESSION['employee_id'];
                // If the bag is entirely rejected
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
                    return;
                }

                
                // Check if one item has been rejected
                $medications = json_decode($form->medications->getValue());
                $i = 0;
                $status = 4;
                if ($numMed = count($medications)) {
                    while($i < $numMed && $medications[$i]->rejected != 1)
                        $i++;
                    if ($i <  $numMed)
                        $status = 7;
                }
                $data['status'] = $status;
                $unusedMeds->edit($data);
                $unusedMeds->updateMedications($medications, true);
                // if an item has been rejected an email is sent regardless the checkbox
                if ($status === 7) {
                    $body = Ontraq_Mail::sendPartiallyRejected(array(
                        'customer_number' => $unusedMeds->getCustomerNumber(),                    	
                        'process' => 'checkin process',
                        'serial_number' =>  $serial_number,
                        'notes' => $form->general_notes->getValue()
                    ));
                }
                elseif ($this->getRequest()->getParam('sendnotes')) {
                    $body = Ontraq_Mail::sendGeneralNotes(array(
                        'customer_number' => $unusedMeds->getCustomerNumber(),						                		
                        'process' => 'checkin process',
                        'serial_number' =>  $serial_number,
                        'notes' => $form->general_notes->getValue()
                    ));

                }
                $this->_redirect('process/list');
            }
        }
        // Check if the Bag has some medication associated
        else {
            $medications = Application_Model_Medication::findAll(array('recovery_id' => $unusedMeds->getRecoveryId()));
            $form->weight->setValue($unusedMeds->getWeight());
            if ($medications && count($medications)) {
                foreach ($medications as $key => $medication) {
                    $medications[$key]['medication_id'] = intval($medication['medication_id']);
                    $medications[$key]['quantity'] = intval($medication['quantity']);
                    $medications[$key]['recovery_id'] = intval($medication['recovery_id']);
                    $medications[$key]['bin_id'] = intval($medication['bin_id']);
                    $medications[$key]['rejected'] = intval($medication['rejected']);
                }
                $form->medications->setValue(json_encode($medications));
            }
        }
        $this->view->current_page = array('menu' => 'process', 'label' => 'Process Unused Medications Bag', 'subtitle' => 'ID Number : '.$serial_number);
        $this->view->serial_number = $serial_number;
        $this->view->form = $form;
    }

    public function addfavoriteAction() {
        $this->_helper->layout->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $response = array('status' => 'error');
        $medication = json_decode(file_get_contents('php://input'));
        if (isset($medication->name) && isset($medication->category)) {
            $favorite = new Application_Model_MedicationFavorite();
            $data = array();
            $data['name'] = $medication->name;
            $data['category'] = $medication->category;
            $data['ndc_number'] = isset($medication->ndc_number) ? $medication->ndc_number : null;
            $data['package'] = isset($medication->package) ? $medication->package : null;
            try {
                if ($fav = $favorite->create($data)) {
                    $response = array('status' => 'success' ,'id' => $fav->getMedicationFavoriteId());
                }    
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        else {
            $response['message'] = 'Parameters are missing';
        }
        $this->_helper->json->sendJson($response); 
    }

    public function unfavoriteAction() {
        $this->_helper->layout->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $response = array('status' => 'error');
        $medication = json_decode(file_get_contents('php://input'));
        if (isset($medication->id)) {
            $favorite = Application_Model_MedicationFavorite::findOne(array('medication_favorite_id' => $medication->id));
            try {
                if ($favorite->remove()) {
                    $response = array('status' => 'success');
                }    
            } 
            catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        else {
            $response['message'] = 'Parameters id is missing';
        }
        $this->_helper->json->sendJson($response); 
    }

    public function revertprocessAction() {
        if (!Application_Model_User::isVendor()) {
            $this->_forward('auth','error');
            return;
        }
        if (!$_SESSION['admin'])
            $this->_redirect('index/scan/serial_number/'.$serial_number);

        // assert serial number is given
        if (!$serial_number =  $this->getRequest()->getParam('serial_number')) {
            $this->_forward('error','error');
            return;
        }
        $this->view->serial_number = $serial_number;

        $recovery = Application_Model_Recovery::findOne(array('serial_number' => $serial_number));
        if (!$recovery || (($recovery->getStatus() != 4 ) && ($recovery->getStatus() != 5) && ($recovery->getStatus() != 6) && ($recovery->getStatus() != 7))) { 
            $this->_redirect('index/scan/serial_number/'.$serial_number);
            return;
        }

        if ($this->getRequest()->isPost()) {
            if (!$confirm =  $this->getRequest()->getParam('confirm')) {
                $this->_redirect('process/list');
                return;
            }
            if ($confirm == 'yes') {
                $data = array('status' => $recovery->getUnprocessedStatus());
                $recovery->edit($data);
                $this->_redirect('index/scan/serial_number/'.$serial_number);
            }
        }
    }
   
}