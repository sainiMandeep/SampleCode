<?php
class Application_Model_UnusedMeds extends Application_Model_Recovery
{

	/**
	 * query database to get one recovery. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_Recovery
	 */
	public static function findOne($params = null) {
		$recovery = parent::findOneRow($params);
		if ($recovery) {
			$unusedMeds = new Application_Model_UnusedMeds();
			$unusedMeds->map($recovery);			
			return $unusedMeds;
		}
		return false;
	}

	public function edit($data = null) {
		if (!$data)
			return false;
		$where = $this->getAdapter()->quoteInto('recovery_id = ?', $this->getRecoveryId());
		try {
			$this->update($data,$where);	
		} 
		catch (Exception $e) {
			error_log(Zend_Debug::dump($e->getMessage()));
		}
	}

	public function updateMedications($medications = null) {
		
		$med = new Application_Model_Medication();

		// remove all existing medications
		$where = $this->getAdapter ()->quoteInto ( 'recovery_id = ?', $this->getRecoveryId() );
		$med->delete ( $where );

		if (!$medications)
			return true;

		foreach ($medications as $medication) {
			$data['quantity'] = $medication->quantity;
			$data['ndc_number'] = $medication->ndc_number;
			$data['name'] = $medication->name;
			$data['package'] = $medication->package;
			$data['category'] = $medication->category;
			$data['recovery_id'] = $this->getRecoveryId();
			$data['bin_id'] = $medication->bin->bin_id;
			$data['rejected'] = $medication->rejected;
			$data['updated_date'] = Ontraq_Date::now('SQL');
			try {
				$key = $med->insert($data);	
			
			} catch (Exception $e) {
				error_log(Zend_Debug::dump($e->getMessage()));
			}
			
		}
	}

	public function getUnprocessedStatus() {
		return 3;
	}
}