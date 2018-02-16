<?php
class BinController extends Zend_Controller_Action {
	public function init() {
		if (!Application_Model_User::isVendor())
			$this->_redirect('/');
		/* Initialize action controller here */
		$this->view->current_page = array('menu' => 'bin', 'label' => 'Destruction Bins', 'subtitle' => 'Manage your bin');
	}

	public function indexAction() { // action body
		$wasteTypes = Application_Model_WasteType::findAll();
		$this->view->wasteTypes = json_encode($wasteTypes);

		$binTypes = Application_Model_Bin::getBinType();
		$this->view->binTypes = json_encode($binTypes);

		$bins = Application_Model_Bin::findAll (array('onlyActive' => true,'qty' => true));

		// Filter bins with is_default
		if ($bins && is_array($bins)) {
			$tempBins = array();
			foreach ($bins as $key => $bin) {
				if ($bin['is_default'] == 1)
					$tempBins[] = $bin;
			}
		}
		
		foreach ($bins as $key=>$bin){
			if($bins[$key]['is_default']){
				if($bins[$key]['is_default'] ==0)
					$bins[$key]['is_default']=false;
				if($bins[$key]['is_default'] ==1)
					$bins[$key]['is_default']=true;
			}				
			if($bins[$key]['start_date'])
				$bins[$key]['start_date'] = Ontraq_Date::sqlToJQUERY($bins[$key]['start_date']);
			if($bins[$key]['close_date'])
				$bins[$key]['close_date'] = Ontraq_Date::sqlToJQUERY($bins[$key]['close_date']);
			if($bins[$key]['destruction_date'])
				$bins[$key]['destruction_date'] = Ontraq_Date::sqlToJQUERY($bins[$key]['destruction_date']);			
		}
		$this->view->bins = json_encode ( $bins );
	}

	public function createAction() {
		$this->_helper->layout->disableLayout ();
		$request = $this->getRequest ();
		$form = new Application_Form_Bin_Create ();
		$locationModel = Application_Model_Location::findAll ();
		$this->view->locations = json_encode ( $locationModel );
		if ($this->getRequest ()->isPost ()) {
			if ($form->isValid ( $request->getPost () )) {
				$bin = new Application_Model_Bin ();
				$bin->create ( $form );
			} else {
				$this->view->error = true;
			}
		}
		$this->view->form = $form;
	}
	public function saveAction() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( true );
		if ($this->getRequest ()->isPost ()) {
			if (! $bins = $this->getRequest ()->getParam ( "bins" )) {
				echo json_encode ( array (
						'status' => 'error' 
				) );
				return;
			}

			foreach ( $bins as $value ) {
				$bin = Application_Model_Bin::findOne ( array (
						'bin_id' => $value ['bin_id'] 
				) );
				$data = array();
				if(isset($value ['is_default']) && !empty($value['is_default'])){
					$bin->setIsDefault ( $value ['is_default'] );
					$data['is_default']= $bin->getIsDefault();
				}
				if(isset($value ['start_date']) && !empty($value['start_date'])){
					$bin->setStartDate ( $value ['start_date'] );
					$data['start_date'] = $bin->getStartDate();
				}
				else{
					$data['start_date'] = null;
				}
				if(isset($value ['close_date']) && !empty($value['close_date'])){
					$bin->setCloseDate ( $value ['close_date'] );
					$data['close_date'] = $bin->getCloseDate();
				}
				else{
					$data['close_date'] = null;
				}
				if(isset($value ['destruction_date']) && !empty($value['destruction_date'])){
					$bin->setDestructionDate ( $value ['destruction_date'] );
					$data['destruction_date'] = $bin->getDestructionDate();
				}
				else{
					$bin->setDestructionDate (null );
					$data['destruction_date'] = null;
				}
				$bin->edit($data);	
			}
		}
	}

	public function editAction() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( true );
		if ($this->getRequest ()->isPost ()) {
			$binJSON = json_decode(file_get_contents('php://input'));
			if (! $binJSON->bin_id) {
				echo json_encode ( array (
						'status' => 'error' ,
						'message' => 'Param bin_id is missing'
				) );
				return;
			}
			$bin = Application_Model_Bin::findOne ( array (
					'bin_id' => $binJSON->bin_id
			) );
			// Check if the bin has items associated
			$medications = Application_Model_Medication::findAll(array('bin_id' => $bin->getBinId()));
			if (!$medications || (count($medications) == 0))
				$emptyBin = true;
			else
				$emptyBin = false;

			$data = array();
			// Update only if the bin is empty
			if($emptyBin && isset($binJSON->waste_type->waste_type_id) && !empty($binJSON->waste_type->waste_type_id)){
				$bin->setWasteTypeId ( $binJSON->waste_type->waste_type_id );
				$data['waste_type_id']= $bin->getWasteTypeId();
			}
			if(isset($binJSON->bin_type->bin_type_id) && !empty($binJSON->bin_type->bin_type_id)){
				$bin->setBinTypeId ( $binJSON->bin_type->bin_type_id );
				$data['bin_type_id']= $bin->getBinTypeId();
			}

			if(isset($binJSON->location_name) && !empty($binJSON->location_name)){
				$bin->setLocation ( $binJSON->location_name );
				$data['location_id']= $bin->getLocationId();
			}
			if(isset($binJSON->number_id) && !empty($binJSON->number_id)){
				// check if the number id is taken
				if ($binDuplicates = Application_Model_Bin::findAll ( array (
					'number_id' => $binJSON->number_id
				))) {
					if ($binDuplicates) {
						if (count($binDuplicates) > 1) {
							echo json_encode(array('status' => 'error','message' => 'The ID number is already taken'));
							return;		
						}
						if (count($binDuplicates) == 1) {
							if ($binDuplicates[0]['bin_id'] != $bin->getBinId()) {
								echo json_encode(array('status' => 'error','message' => 'The ID number is already taken'));
								return;	
							}
						}
					}
				}
				$bin->setNumberId ( $binJSON->number_id );
				$data['number_id']= $bin->getNumberId();
			}
			try {
				$bin->edit($data);						
			}
			catch(Exception $e) {
				echo json_encode(array('status' => 'error','message' => 'Error during the update'));
				return;
			}
			echo json_encode(array('status' => 'success'));
			return;
		}
	}

	public function deleteAction() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( true );
		if ($this->getRequest ()->isPost ()) {
			$binJSON = json_decode(file_get_contents('php://input'));
			if (! $binJSON->bin_id) {
				echo json_encode ( array (
						'status' => 'error' ,
						'message' => 'Param bin_id is missing'
				) );
				return;
			}
			$bin = Application_Model_Bin::findOne ( array (
					'bin_id' => $binJSON->bin_id
			) );
			if (!$bin) {
				echo json_encode ( array (
						'status' => 'error' ,
						'message' => 'Bin not found'
				) );
				return;	
			}
			// Check if the bin has items associated
			// If there are one or more items then the bin is not deleted
			$medications = Application_Model_Medication::findAll(array('bin_id' => $bin->getBinId()));
			if ($medications && (count($medications) > 0)) {
				echo json_encode ( array (
						'status' => 'error' ,
						'message' => 'The bin is not empty. Ontly empty bins can be deleted'
				) );
				return;
			}

			if ($bin->remove()) {
				echo json_encode ( array (
						'status' => 'success'
				) );
				return;		
			}
			else {
				echo json_encode ( array (
						'status' => 'error' ,
						'message' => 'Error during the deletion'
				) );
				return;			
			}
		}
	}

	public function editlocationAction() {
		$this->_helper->layout->disableLayout ();
		$form = new Application_Form_Bin_EditLocation ();
		$locationModel = Application_Model_Location::findAll ();
		$this->view->locations = json_encode ( $locationModel );
		if (! $this->getRequest ()->getParam ( 'token', 'num' )) {
			$this->_redirect ( '/error/error' );
		}
		if ($this->getRequest ()->isGet ()) {
			$bin_id = $this->getRequest ()->getParam ( 'token' );
			$location_id = $this->getRequest ()->getParam ( 'num' );
			$bin = Application_Model_Bin::findOne ( array (
					'bin_id' => $bin_id 
			) );
			if (! $bin) {
				$this->_redirect ( '/error/error' );
			}
			$location = Application_Model_Location::findOne ( array (
					'location_id' => $location_id 
			) );
			if (! $location) {
				$this->_redirect ( '/error/error' );
			}
			$form->populate ( array (
					'token' => $bin->getBinId (),
					'binlocation' => $location->getName () 
			) );
		} else {
			if ($this->getRequest ()->isPost ()) {
				if ($form->isValid ( $this->getRequest ()->getPost () )) {					
					$bin = new Application_Model_Bin ();
					$bin->editlocation ( $form ); 
				} else {
					$this->view->error = true;
				}
			}
		}
		$this->view->form = $form;
	}
}