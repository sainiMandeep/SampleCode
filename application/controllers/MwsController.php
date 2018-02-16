<?php

class MwsController extends Zend_Controller_Action
{
    public function init()
    {
        if (!Application_Model_User::isVendor()) {
            $this->_redirect('/');
        }
                
        $this->_helper->layout->setLayout('default');
        $this->view->no_breadcrumb = true;
    }

    public function scanAction() 
    {        
        if (!Application_Model_User::isVendor()) {
            $this->_forward('auth', 'error');
            return;
        }        
        $this->view->current_page = array(
                'menu' => 'mws', 
                'label' => 'Scan Pharmaceutical boxes/envelopes, and Sharps', 
                'subtitle' => 'Process or cheked in recovery serial number'
        );
    }

    public function processAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();        
        if (!$request->isPost()) {
            return;        
        }
        $params = json_decode($request->getRawBody(), true);

        if(!isset($params['actionType'])) {
            $response = array(
                'title' =>  'Action Type is missing',
                'message' => 'Please select valid action type.' 
            );                
            $this->sendError(409, $response);
            return false;
        }
        $data = array();

        $tmp = explode('-', $params['serialNumber']);            
        $itemNumber = $tmp[0]; 

        //validate item number
        if (isset($itemNumber)) {        
            $itemNumberValidator = new Recovery_Validators_Items();
                       
            if (!$itemNumberValidator->isValid($itemNumber)) {

                $validator = $itemNumberValidator->getMessages();
                foreach ($validator as $key => $val) {
                    $message = $val;
                }
                $response = array(
                    'title' =>  'Item Number Invalid.',
                    'message' =>  $message
                );                
                $this->sendError(409, $response);
                return false;
            }
            $itemsModel = Application_Model_Products::findOne($itemNumber);            
            $data['item_number'] = $itemsModel["item_number"];
        }

        //validate serial number
        if (isset($params['serialNumber'])) {
            $serialNumberValidator = new Recovery_Validators_SerialNumber();           
            if (!$serialNumberValidator->isValid($params['serialNumber'])) {
                $validator = $serialNumberValidator->getMessages();
                foreach ($validator as $key => $val) {
                    $message = $val;
                }
                $response = array(
                    'title' =>  'Serial number Invalid',
                    'message' =>  $message
                );                
                $this->sendError(409, $response);
                return false;
            }            
            $data['serial_number'] = $params['serialNumber'];
        }

        //after deleting weight in input field send empty string
        if (isset($params['weight']) && $params['weight'] != "") {
            $weightValidator = new Zend_Validate_Float();  
            $weightValidator->setMessage('Please check entered weight.');        
            if (!$weightValidator->isValid($params['weight'])) {            
                $validator = $weightValidator->getMessages();
                foreach ($validator as $key => $val) {
                    $message = $val;
                }                             
                $response = array(
                    'title' =>  'Weight Invalid',
                    'message' =>  $message
                );                
                $this->sendError(409, $response);
                return false;
            }
            $data['weight'] = $params['weight'];            
        }

        //check if serial number exists in recovery table
        $recovery = Application_Model_Recovery::findOneRow(array('serial_number' => $params['serialNumber']));        
        $recoveryType = Application_Model_Products::getRecoveryType($itemNumber);
        
        if(isset($recoveryType)) {
            $data['recovery_type'] = $recoveryType;
        }

        if(isset($_SESSION['vendor'])) {
            $data['vendor'] = $_SESSION['vendor'];
        }

        $today = date('Y-m-d H:i:s');                
        $data['checkin_date'] = $today;
        
        if (isset($params['note'])) {
            $data['general_notes'] = $params['note'];        
        }
        

        if ($recovery && isset($recovery['serial_number'])) {        
            $data['updated_date'] = $today;

            if ($params['actionType'] == 'checkin') {
                if (isset($recovery['checkin_date'])) {                   
                    $message = $recovery['serial_number'].' is already checkedin, please select process option to process.';
                    $response = array(
                        'title' =>  'Checkedin Serial Number',
                        'message' =>  $message
                    );                
                    $this->sendError(409, $response);
                    return false;    
                }
                $data['status'] = 3;
                try {
                    $response = $this->update($data, 'checkedin'); 
                    return $response;    
                } catch (Exception $e) {
                    $this->sendError(500, $e->getMessage());
                    return;
                }                
            }            
            elseif ($params['actionType'] == 'process') {                 
                if (isset($recovery['processed_date'])) {                   
                    $message = $recovery['serial_number'].' is processed on '. date('m/d/Y', strtotime($recovery['processed_date']));
                    $response = array(
                        'title' =>  'Processed Serial Number',
                        'message' =>  $message
                    );                
                    $this->sendError(409, $response);
                    return false;
                }
                $data['status'] = 4;
                $data['processed_date'] = $today;
                try {
                    $response = $this->update($data, 'processed');
                    return $response;    
                } catch (Exception $e) {
                    $this->sendError(500, $e->getMessage());
                    return;
                }
                
            }            
        }      
        
        if ($params['actionType'] == 'checkin') {
                $data['checkin_date'] = $today;
                $data['status'] = 3;
                $action = 'checkedin';
        }
        elseif ($params['actionType'] == 'process') {
            $data['processed_date'] = $today;
            $data['status'] = 4;
            $action = 'processed';
        }
        try {
            $response = $this->insert($data, $action);
            return $response;  
        } catch (Exception $e) {
            $this->sendError(500, $e->getMessage());
            return;            
        }           
        
    }

    private function update($recovery, $action)
    {
        if(!$recovery['serial_number']) {
            throw new Exception("Error While Processing Updating Data Request", 1);            
        }
        $where = array('serial_number = ?' => $recovery['serial_number']);
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        try {
            $key = $dbAdapter->update('recovery', $recovery, $where);
            $message =$recovery['serial_number'] .' is ' . $action .' successfully.';
            $response = array(
                'title' =>  'Processed Serial Number',
                'message' =>  $message
            );                
            $this->sendError(200, $response);       
           } catch (Exception $e) {
                $this->sendError(500, $e->getMessage());
                return;
            }
    }

    private function insert($recovery, $action)
    {
        if (!$recovery) {
            throw new Exception("Error Processing Request", 1);           
        }
       
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        try {
            $dbAdapter->insert(
            'recovery',
            $recovery
        );
        
        $response = array(
            'title' => 'Success!',
            'message' => $recovery['serial_number'] .' is ' .$action. ' successfully.'              
        );        
            $this->sendResponse(201, $response);
            return;        
        } catch (Exception $e) {
            $this->sendError(500, $e->getMessage());
            return;
        }         
    }

    public function weightupdateAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;        
        } 
        $checkedinRecovery = $request->getPost();
       
        $validator = new Zend_Validate_Float();
        $validator->setMessage('Please enter valid weight.');
        if (!$validator->isValid($checkedinRecovery['value'])) {
            $messages = $validator->getMessages();
            foreach ($messages as $key => $value) {
                $message = $value;
            }
            $this->sendError(409, $message);
            return false;
        }
        $recovery = array(
            'weight' => $checkedinRecovery['value'],
            'updated_date' => date('Y-m-d H:i:s')            
        );

        try {
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();;
            $where = array('recovery_id= ?' => $checkedinRecovery['id']);      
            $key = $dbAdapter->update('recovery', $recovery, $where);            
            $message = 'Weight is updated successfully.';
            $response = array(
                'title' =>  'Data is updated',
                'message' =>  $message
            );                
            $this->sendError(200, $response);       
           } catch (Exception $e) {
                $this->sendError(500, $e->getMessage());
                return;
        }
    }

    public function checkedinAction()
    {        
        $this->view->current_page = array(
                'menu' => 'checked_in', 
                'label' => 'Checked in Items', 
                'subtitle' => 'Checked in serial numbers which are not procssed yet.'
        );                
                
    }

    public function bulkprocessAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();
        
        if ($request->isGet()) {            
            $recovery = Application_Model_Recovery:: getMWSCheckedInRecovery();            
            $this->sendResponse(200, $recovery);
            return; 
        }
        if($request->isPost()) {
            $params = $request->getParams();            
            $recoveryIds = $params['processRecovery'];

            if (!$recoveryIds) {
                $response = array(
                'title' => 'Error',
                'message' =>'An error occured while grabbing recovery bulk process data.'
            );
                $this->sendError(404, $response);
                return;
            }        

            $today = date('Y-m-d H:i:s');                        
            $data = array(
                'processed_date' => $today,
                'status' => 4
            );            
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            $where = $dbAdapter->quoteInto('recovery_id IN (?)', $recoveryIds);
            
            $key = $dbAdapter->update('recovery', $data, $where);
            if($key) {
                $this->_redirect('/mws/checkedin');    
            }
            else {
                $this->_forward('error','error');
                return;
            } 
        }                        
    }

    public function reportAction()
    {
        if (!Application_Model_User::isVendor()) {
            $this->_forward('auth', 'error');
            return;
        } 
        $this->view->current_page = array(
                'menu' => 'report', 
                'label' => 'MWS Report', 
                'subtitle' => 'Generate report of processed recovery items.'
        );

        $request = $this->getRequest();        
        
        if($request->isPost()) {            
            $params = $this->getRequest()->getParams();

            if(!$params['startDate'] || !$params['endDate']){
                return false;
            }           
            
            $recoveryModel = new Application_Model_Recovery();

            if($params['category'] === 'detailed') {
                return $this->detailedReport($params['startDate'], $params['endDate']);
            }
            $reportData =  $recoveryModel->mwsReport($params['startDate'], $params['endDate']);                           
            $this->view->mwsreport = $reportData;   
        }
    } 

    public function detailedReport($startDate, $endDate)
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $recoveryModel = new Application_Model_Recovery();        
        $reportData = $recoveryModel->getDetailedReport($startDate, $endDate);
      
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
        ->setTitle("MWS Recovery Report")
        ->setSubject("Report")
        ->setDescription("");

        $current_item_number = '';
        $indice = 0;
        $table_rows_start = 1;
        if ($reportData) {
            foreach ($reportData as $data) {
                if ($data['item_number'] != $current_item_number) {
                    $table_current_row = $table_rows_start + 1;
                    $current_item_number = $data['item_number'];  
                    // each item number we use the separate sheet
                    if ($indice == 0) {
                        $objPHPExcel->setActiveSheetIndex(0);
                        $indice++;
                    }
                
                // otherwise create new sheet
                else {
                    $objPHPExcel->createSheet(null, $indice);
                    $objPHPExcel->setActiveSheetIndex($indice);
                    $indice++;
                }
                $objPHPExcel->getActiveSheet()->setTitle($data['item_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "Name") 
                    ->setCellValue('B1', "Item Number") 
                    ->setCellValue('C1', "Weight") 
                    ->setCellValue('D1', "Processed date")
                    ->setCellValue('E1', "Notes");

                $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);                 
            }

            $today = date("Y-m-d");
            $objPHPExcel->getActiveSheet()                
                ->setCellValue('A'.$table_current_row, $data['name'])
                ->setCellValue('B'.$table_current_row, $data['item_number'])
                ->setCellValue('C'.$table_current_row, $data['weight'])
                ->setCellValue('D'.$table_current_row, $data['processed_date'])
                ->setCellValue('E'.$table_current_row, $data['general_notes']);

                $table_current_row++;
            }         
        }      
        $objPHPExcel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="MWS_recovery_'.$today.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    
    private function sendError($responseCode, $errorMessage) 
    {
        $response = $this->getResponse();
        $response->setHttpResponseCode($responseCode);
        $response->setHeader('Content-Type', 'application/json');
        $response->appendBody(json_encode($errorMessage, JSON_PRETTY_PRINT));        
    }

    private function sendResponse($responseCode, $responseObject = null) 
    {
        $response = $this->getResponse();
        $response->setHttpResponseCode($responseCode);
        $response->setHeader('Content-Type', 'application/json');
        if ($responseObject !== null) {
            $response->appendBody(json_encode($responseObject, JSON_PRETTY_PRINT));

        }
    }
}
