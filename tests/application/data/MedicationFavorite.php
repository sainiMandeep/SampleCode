<?php 
class MedicationFavorite {
	public static function generate($params = null) {
		$data = array(
			'name' => 'unit name',
			'package_description' => 'unit package description',
			'waste_type' => 'unit waste type',
			'ndc_number' => '0000-0000-00',
		);

		$medicationFavorite = new Application_Model_MedicationFavorite();
		$where = $medicationFavorite->getAdapter()->quoteInto('name = ?',$name);
		$medicationFavorite->delete($where);
		if ($medication_favorite_id = $medicationFavorite->insert($data)) {
			$data['medication_favorite_id'] = $medication_favorite_id;
			return $data;
		}
	}
}