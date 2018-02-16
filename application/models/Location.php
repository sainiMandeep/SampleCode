<?php
class Application_Model_Location extends Zend_Db_Table_Abstract{
	protected $_name = 'location';
	protected $_primary = 'location_id';
	public function getLocationId() {
		if (isset ( $this->location_id ))
			return ($this->location_id);
		return false;
	}
	public function setLocationId($location_id) {
		$this->location_id = $location_id;
	}
	public function getName() {
		if (isset ( $this->name))
			return (trim($this->name));
		return false;
	}
	public function setName($name) {
		$this->name = trim($name);
	}
	public function getCreatedDate() {
		if (isset ( $this->created_date))
			return ($this->created_date);
		return false;
	}
	public function setCreatedDate($created_date) {
		$this->created_date = ($created_date);
	}
	public static function findAll() {
		$location = new Application_Model_Location();
		$select = $location->select ();
		$locations = $location->fetchAll ( $select );
		if ($locations) {
			return $locations->toArray ();
		}
		return false;  
	}
	public static function findOne($params = array()) {		
		$location = new Application_Model_Location();
		$select = $location->select ();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', trim($value));
			}
		}
		if ($row = $location->fetchRow($select)) {
			$location->setLocationId($row->location_id);
			$location->setName(trim($row->name));
			$location->setCreatedDate($row->created_date);
			return $location;
		}
		return false;
	}
	
}