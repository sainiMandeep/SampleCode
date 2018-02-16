<?php
class Application_Model_MedicationFavorite extends Zend_Db_Table_Abstract{
	protected $_name = 'medication_favorite';
	protected $_primary = 'medication_favorite_id';
	
	/**
	* Get medication_favorite
	* @return medication_favorite
	*/
	public function getMedicationFavoriteId() {
		if (isset($this->medication_favorite))
			return $this->medication_favorite;
		return false;
	}
	
	/**
	* Set medication_favorite
	* @param medication_favorite
	*/
	public function setMedicationFavoriteId($medication_favorite) {
		$this->medication_favorite = $medication_favorite;
	}


	/**
	* Get name
	* @return name
	*/
	public function getName() {
		if (isset($this->name))
			return $this->name;
		return false;
	}
	
	/**
	* Set name
	* @param name
	*/
	public function setName($name) {
		$this->name = $name;
	}

	/**
	* Get package
	* @return package
	*/
	public function getPackage() {
		if (isset($this->package))
			return $this->package;
		return false;
	}
	
	/**
	* Set package
	* @param package
	*/
	public function setPackage($package) {
		$this->package = $package;
	}

	/**
	* Get category
	* @return category
	*/
	public function getCategory() {
		if (isset($this->category))
			return $this->category;
		return false;
	}
	
	/**
	* Set category
	* @param category
	*/
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	* Get ndc_number
	* @return ndc_number
	*/
	public function getNdcNumber() {
		if (isset($this->ndc_number))
			return $this->ndc_number;
		return false;
	}
	
	/**
	* Set ndc_number
	* @param ndc_number
	*/
	public function setNdcNumber($ndc_number) {
		$this->ndc_number = $ndc_number;
	}

	/**
	* Get created_date
	* @return created_date
	*/
	public function getCreatedDate() {
		if (isset($this->created_date))
			return $this->created_date;
		return false;
	}
	
	/**
	* Set created_date
	* @param created_date
	*/
	public function setCreatedDate($created_date) {
		$this->created_date = $created_date;
	}

	/* create favorite in database */
	public function create($data) {
		try {
			$key = $this->insert ($data);	
		} 
		catch (Exception $e) {
			throw new Zend_Exception("Error during insert", 1);
		}
		
		if ($key) {
			$medicationFavorite = new Application_Model_MedicationFavorite();
			$medicationFavorite->setMedicationFavoriteId($key);
			$medicationFavorite->setName($data['name']);
			$medicationFavorite->setPackage($data['package']);
			$medicationFavorite->setCategory($data['category']);
			$medicationFavorite->setNdcNumber($data['ndc_number']);
			return $medicationFavorite;
		} 
		return false;
	}

	public static function findAll() {
		$medicationFavorite = new Application_Model_MedicationFavorite();
		$select = $medicationFavorite->select ();
		$select->from($medicationFavorite->_name);
		$medicationFavorites = $medicationFavorite->fetchAll ( $select );
		if ($medicationFavorites) {
			return $medicationFavorites->toArray ();
		}
		return false;  
	}

	/**
	 * query database to get one medicationFavorite. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_MedicationFavorite
	 */
	public static function findOne($params = array()) {
		$medicationFavorite = new Application_Model_MedicationFavorite();
		$select = $medicationFavorite->select();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value);
			}
		}
		if (!is_array($params)) {
			$select->where('medication_favorite_id = ?', $Params);
		}

		if ($row = $medicationFavorite->fetchRow($select)) {
			$medicationFavorite->setMedicationFavoriteId($row->medication_favorite_id);
			$medicationFavorite->setName($row->name);
			$medicationFavorite->setPackage($row->package);
			$medicationFavorite->setCategory($row->category);
			$medicationFavorite->setCreatedDate($row->created_date);
			return $medicationFavorite;
		}
		return false;
	}

	public function remove() {
		$key = $this->getMedicationFavoriteId();
		if (! $key) {
			throw new Zend_Exception ( "Params are required", 1 );
		}
		$where = $this->getAdapter ()->quoteInto ( 'medication_favorite_id = ?', $key );
		return parent::delete ( $where );
	}	
}