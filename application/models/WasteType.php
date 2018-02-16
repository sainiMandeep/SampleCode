<?php
class Application_Model_WasteType extends Zend_Db_Table_Abstract{
	protected $_name = 'waste_type';
	protected $_primary = 'waste_type_id';
	
	public static function findAll() {
		$wasteType = new Application_Model_WasteType();
		$select = $wasteType->select ();
		$select->from($wasteType->_name,array('waste_type_id','name'));
		$wasteTypes = $wasteType->fetchAll ( $select );
		if ($wasteTypes) {
			return $wasteTypes->toArray ();
		}
		return false;  
	}
}