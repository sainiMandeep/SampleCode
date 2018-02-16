<?php
class Application_Model_MedicationNDC extends Zend_Db_Table_Abstract{
	protected $_name = 'imported_medications';
	protected $_primary = 'imported_medications_id';
	protected $_schema = DB_TRACKER_NAME;
	
	public static function findAll($search = false) {
		$medicationNDC = new Application_Model_MedicationNDC();
		$query = "select top 100 PROPRIETARYNAME as name,PACKAGEDESCRIPTION as package,NDCPACKAGECODE as ndc_number, medication_favorite_id as favorite from ".$medicationNDC->_schema.".dbo.".$medicationNDC->_name." med";
		$query .= " join ".$medicationNDC->_schema.".dbo.imported_medications_packages pack on pack.PRODUCTNDC = med.PRODUCTNDC";
		$query .= " left join medication_favorite fav on fav.ndc_number = pack.NDCPACKAGECODE";
		
		if ($search) {
			$query .= " where (PROPRIETARYNAME like '%".$search."%'";
			$query .= " or PACKAGEDESCRIPTION like '%".$search."%'";
			$query .= " or NDCPACKAGECODE like '%".$search."%')";
		}
		// echo $query;
		$ndc =  $medicationNDC->getDefaultAdapter ()->query ( $query)
						->fetchAll();
		if ($ndc)
			return $ndc;
		return false;
	}
}