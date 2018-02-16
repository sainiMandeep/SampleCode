<?php
class Application_Model_Bin extends Zend_Db_Table_Abstract {
	protected $_name = 'bin';
	protected $_primary = 'bin_id';
	public function getBinId() {
		if (isset ( $this->bin_id ))
			return $this->bin_id;
		return false;
	}
	public function setBinId($bin_id) {
		$this->bin_id = $bin_id;
	}
	public function getWasteTypeId() {
		if (isset ( $this->waste_type_id ))
			return $this->waste_type_id;
		return false;
	}
	public function setWasteTypeId($waste_type_id) {
		$this->waste_type_id = $waste_type_id;
	}
	public function getBinTypeId() {
		if (isset ( $this->bin_type_id ))
			return $this->bin_type_id;
		return false;
	}
	public function setBinTypeId($bin_type_id) {
		$this->bin_type_id = $bin_type_id;
	}
	public function getLocationId() {
		if (isset ( $this->location_id ))
			return trim ( $this->location_id );
		return false;
	}
	public function setLocationId($location_id) {
		$this->location_id = $location_id;
	}
	public function getNumberId() {
		if (isset ( $this->number_id ))
			return $this->number_id;
		return false;
	}
	public function setNumberId($number_id) {
		$this->number_id = $number_id;
	}
	public function getIsDefault() {
		if (isset ( $this->is_default ))
			return $this->is_default;
		return false;
	}
	public function setIsDefault($is_default) {
		$this->is_default = $is_default;
	}
	public function getStartDate() {
		if (isset ( $this->start_date ))
			return $this->start_date;
		return false;
	}
	public function setStartDate($start_date) {
		$this->start_date = $start_date;
	}
	public function getCloseDate() {
		if (isset ( $this->close_date ))
			return $this->close_date;
		return false;
	}
	public function setCloseDate($close_date) {
		$this->close_date = $close_date;
	}
	public function getDestructionDate() {
		if (isset ( $this->destruction_date ))
			return $this->destruction_date;
		return false;
	}
	public function setDestructionDate($destruction_date) {
		$this->destruction_date = $destruction_date;
	}
	/**
	* Get bin_type_name
	* @return bin_type_name
	*/
	public function getBinTypeName() {
		if (isset($this->bin_type_name))
			return $this->bin_type_name;
		return false;
	}
	
	/**
	* Set bin_type_name
	* @param bin_type_name
	*/
	public function setBinTypeName($bin_type_name) {
		$this->bin_type_name = $bin_type_name;
	}


	/**
	 * Return the id associated with the location_name
	 * If location_name is new, then create a new location
	 * otherwise return the existing location_id
	 * @param  string $location_name
	 * @return int  location_id, false if error
	 */
	private function getLocation($location_name = null) {
		if (!$location_name)
			throw new Zend_Exception("Param location_name is required", 1);

		$location = Application_Model_Location::findOne ( array (
				'name' => $location_name
		));
		if (! $location) {
			$location = new Application_Model_Location ();
			try {
				$location_key = $location->insert ( array (
						"name" => trim ( $location_name ) 
				) );
			} 
			catch ( Exception $e ) {
				throw new Zend_Exception ( 'Error during insert location.' );
			}
			return $location_key;
		}
		else {
			return $location->getLocationId ();
		}
	}


	/**
	 * Given the location_name, set the location_id and return it
	 * If location_name is new, then create a new location_id
	 * @param description $location_name
	 * @return int  location_id, false if error
	 */
	public function setLocation($location_name = null) {
		if (!$location_name)
			throw new Zend_Exception("Param location_name is required", 1);

		$location_id = $this->getLocation ( $location_name );
		if ($location_id) {
			$this->setLocationId($location_id);
			return $location_id;
		}
		return false;
	}

	public function create($form) {
		if ($form->binlocation->getValue ()) {
			$location_id = $this->getLocation ( $form->binlocation->getValue () );
			$this->setLocationId($location_id);

			if ($form->wastetype->getValue ()) {
				$bin = Application_Model_Bin::findAll ( array (
						'onlyActive' => true,
						'is_default' => true,
						'waste_type_id' => $form->wastetype->getValue () 
				) );
				if (! $bin) {
					$this->setIsDefault ( true );
				} else {
					$this->setIsDefault ( false );
				}
			}
			$this->setWasteTypeId ( $form->wastetype->getValue () );
			$this->setBinTypeId ( $form->bintype->getValue () );
			$this->setNumberId ( $form->number_id->getValue () );
			try {
				$key = $this->insert ( array (
						'waste_type_id' => $this->getWasteTypeId (),
						'bin_type_id' => $this->getBinTypeId (),
						'location_id' => trim ( $this->getLocationId () ),
						'number_id' => $this->getNumberId (),
						'is_default' => $this->getIsDefault () 
				) );
			} catch ( Exception $e ) {
				throw new Zend_Exception ( 'Error during insert bin.' );
			}
			if ($key) {
				return $key;
			} else {
				return false;
			}
		}
	}
	public static function getMapWasteType() {
		$binModel = new Application_Model_Bin ();
		$select = $binModel->select ();
		$select->setIntegrityCheck ( false );
		$select->from ( 'waste_type', array (
				'name' 
		) );
		try {
			$rows = $binModel->fetchAll ( $select );
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage ( 'Error during fetching waste type.' ) );
		}
		if (! $rows)
			return array ();
		$rows = $rows->toArray ();
		$waste_types = array ();
		foreach ( $rows as $key => $row ) {
			$waste_types [$key + 1] = $row ['name'];
		}
		return $waste_types;
	}
	public static function getMapBinType() {
		$binModel = new Application_Model_Bin ();
		$select = $binModel->select ();
		$select->setIntegrityCheck ( false );
		$select->from ( 'bin_type', array (
				'name' 
		) );
		try {
			$rows = $binModel->fetchAll ( $select );
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage ( 'Error during fetching bin type.' ) );
		}
		if (! $rows)
			return array ();
		$rows = $rows->toArray ();
		$bin_types = array ();
		foreach ( $rows as $key => $row ) {
			$bin_types [$key + 1] = $row ['name'];
		}
		return $bin_types;
	}

	public static function getBinType() {
		$binModel = new Application_Model_Bin ();
		$select = $binModel->select ();
		$select->setIntegrityCheck ( false );
		$select->from ( 'bin_type', array (
				'bin_type_id','name'
		) );
		try {
			$rows = $binModel->fetchAll ( $select );
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage ( 'Error during fetching bin type.' ) );
		}
		if (! $rows)
			return array ();
		return $rows->toArray ();
	}

	public static function findAll($params = null) {
		// default value
		$onlyActive = true;
		$is_default = false;
		$waste_type_id = false;
		$number_id = false;
		$qty = false;
		if ($params && is_array ( $params )) {
			if (isset ( $params ['onlyActive'] ))
				$onlyActive = $params ['onlyActive'];
			if (isset ( $params ['is_default'] ))
				$is_default = $params ['is_default'];
			if (isset ( $params ['waste_type_id'] ))
				$waste_type_id = $params ['waste_type_id'];
			if (isset ( $params ['number_id'] ))
				$number_id = $params ['number_id'];
			if (isset ( $params ['qty'] ))
				$qty = $params ['qty'];
		}


		$bins = new Application_Model_Bin ();
		$select = $bins->select ();
		if ($qty)
			$select->from ( $bins->_name,array('bin_id','number_id','start_date','close_date','destruction_date','is_default',"qty"=>"sum(case when medication.medication_id > 1 THEN 1 ELSE 0 END)") );
		else
			$select->from ( $bins->_name);
		$select->setIntegrityCheck ( false );
		$select->join ( 'location', 'location.location_id= bin.location_id', array (
				'location_name' => 'name' 
		) );
		$select->join ( 'waste_type', 'waste_type.waste_type_id= bin.waste_type_id', array (
				'waste_type_name' => 'name',
				'waste_type_id'
		) );
		$select->join ( 'bin_type', 'bin_type.bin_type_id= bin.bin_type_id', array (
				'bin_type_name' => 'name',
				'bin_type_id'
		) );
		
		$select->order ( 'waste_type.name' );
		$select->order ( 'start_date desc' );

		if ($qty) {
			$select->joinLeft ( 'medication', 'medication.bin_id= bin.bin_id', array () );
			$select->group ( 'bin.bin_id' );
			$select->group ( 'is_default' );
			$select->group ( 'close_date' );
			$select->group ( 'destruction_date' );
			$select->group ( 'number_id' );
			$select->group ( 'waste_type.name' );
			$select->group ( 'location.name' );
			$select->group ( 'bin_type.name' );
			$select->group ( 'start_date' );
			$select->group ( 'waste_type.waste_type_id' );
			$select->group ( 'bin_type.bin_type_id' );
		}
		if ($onlyActive)
			$select->where ( 'destruction_date is null' );
		if ($is_default)
			$select->where ( 'is_default = ?', $is_default );
		if ($waste_type_id)
			$select->where ( 'bin.waste_type_id = ?', $waste_type_id );
		if ($number_id)
			$select->where ( 'bin.number_id = ?', $number_id );

		try {
			if ($rows = $bins->fetchAll ( $select )) {
				return $rows->toArray ();
			}
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage () );
		}
		return false;
	}
	public static function findOne($params = array()) {
		$bins = new Application_Model_Bin ();
		$select = $bins->select ();
		$select->from ( $bins->_name );
		$select->setIntegrityCheck ( false );
		if (is_array ( $params )) {
			foreach ( $params as $param => $value ) {
				if ($param == 'onlyActive') {
					$select->where ( 'destruction_date is null' );
				}
				else
					$select->where ( $param . ' = ?', $value );
			}
		}
		$select->join ( 'location', 'location.location_id= bin.location_id', array (
				'location_name' => 'name' 
		));
		$select->join ( 'waste_type', 'waste_type.waste_type_id= bin.waste_type_id', array (
				'waste_type_name' => 'name' 
		));
		$select->join ( 'bin_type', 'bin_type.bin_type_id= bin.bin_type_id', array (
				'bin_type_name' => 'name' 
		));
		try {
			if ($row = $bins->fetchRow ( $select )) {
				$bins->setBinId ( $row->bin_id );
				$bins->setNumberId ( $row ['number_id'] );
				$bins->setBinTypeName ( $row ['bin_type_name'] );
				return $bins;
			}
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage () );
		}
		return false;
	}
	public function edit($data) {
		if (! $data)
			return false;
		$where = $this->getAdapter ()->quoteInto ( 'bin_id = ?', $this->getBinId () );
		try {
			$this->update ( $data, $where );
		} catch ( Exception $e ) {
			Zend_Debug::dump ( $e->getMessage () );
		}
	}
	public function editlocation($form) {
		if ($form->binlocation->getValue ()) {
			$location = Application_Model_Location::findOne ( array (
					'name' => $form->binlocation->getValue () 
			) );
			if (! $location) {
				$location = new Application_Model_Location ();
				try {
					$location_key = $location->insert ( array (
							"name" => $form->binlocation->getValue () 
					) );
				} catch ( Exception $e ) {
					throw new Zend_Exception ( 'Error during insert location.' );
				}
				$this->setLocationId ( $location_key );
			} else {
				$this->setLocationId ( $location->getLocationId () );
			}
			$this->setBinId ( $form->token->getValue () );
			$where = $this->getAdapter ()->quoteInto ( 'bin_id = ?', $this->getBinId () );
			$key = $this->getLocationId ();
			$data = array (
					'location_id' => $key 
			);
			try {
				$this->update ( $data, $where );
			} catch ( Exception $e ) {
				throw new Zend_Exception ( $e->getMessage (), 1 );
			}
			if ($key) {
				return $key;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Check that a bin exist for each waste type is is_default set as true
	 * 
	 * @return bool
	 */
	public static function missing($bins, $wasteTypes) {
		if (! $wasteTypes)
			throw new Zend_Exception ( "Waste Type is required", 1 );
		$hashWastTypes = array ();
		foreach ( $wasteTypes as $wasteType ) {
			$hashWastTypes [$wasteType ['name']] = true;
		}
		
		if (! $bins || (count ( $bins ) == 0))
			return hashWastTypes;
		
		foreach ( $bins as $key => $bin ) {
			if (isset ( $hashWastTypes [$bin ['waste_type_name']] ))
				unset ( $hashWastTypes [$bin ['waste_type_name']] );
			if (count ( $hashWastTypes ) == 0)
				return false;
		}
		return $hashWastTypes;
	}

	public function remove() {
		$key = $this->getBinId();
		if (! $key) {
			throw new Zend_Exception ( "Params are required", 1 );
		}
		$where = $this->getAdapter ()->quoteInto ( 'bin_id = ?', $key );
		return parent::delete ( $where );
	}	
}