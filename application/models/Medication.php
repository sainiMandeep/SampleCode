<?php
class Application_Model_Medication extends Zend_Db_Table_Abstract
{

	protected $_name = 'medication';
	protected $_primary = 'medication_id';

	/**
	* Get medication_id
	* @return medication_id
	*/
	public function getMedicationId() {
		if (isset($this->medication_id))
			return $this->medication_id;
		return false;
	}
	
	/**
	* Set medication_id
	* @param medication_id
	*/
	public function setMedicationId($medication_id) {
		$this->medication_id = $medication_id;
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
	* Get quantity
	* @return quantity
	*/
	public function getQuantity() {
		if (isset($this->quantity))
			return $this->quantity;
		return false;
	}
	
	/**
	* Set quantity
	* @param quantity
	*/
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	* Get ndc_number
	* @return ndc_number
	*/
	public function getNDCNumber() {
		if (isset($this->ndc_number))
			return $this->ndc_number;
		return false;
	}
	
	/**
	* Set ndc_number
	* @param ndc_number
	*/
	public function setNDCNumber($ndc_number) {
		$this->ndc_number = $ndc_number;
	}

	/**
	* Get recovery_id
	* @return recovery_id
	*/
	public function getRecoveryId() {
		if (isset($this->recovery_id))
			return $this->recovery_id;
		return false;
	}
	
	/**
	* Set recovery_id
	* @param recovery_id
	*/
	public function setRecoveryId($recovery_id) {
		$this->recovery_id = $recovery_id;
	}

	/**
	* Get bin_id
	* @return bin_id
	*/
	public function getBinId() {
		if (isset($this->bin_id))
			return $this->bin_id;
		return false;
	}
	
	/**
	* Set bin_id
	* @param bin_id
	*/
	public function setBinId($bin_id) {
		$this->bin_id = $bin_id;
	}

	/**
	* Get rejected
	* @return rejected
	*/
	public function getRejected() {
		if (isset($this->rejected))
			return $this->rejected;
		return false;
	}
	
	/**
	* Set rejected
	* @param rejected
	*/
	public function setRejected($rejected) {
		$this->rejected = $rejected;
	}

	/**
	* Get updated_date
	* @return updated_date
	*/
	public function getUpdatedDate() {
		if (isset($this->updated_date))
			return $this->updated_date;
		return false;
	}
	
	/**
	* Set updated_date
	* @param updated_date
	*/
	public function setUpdatedDate($updated_date) {
		$this->updated_date = $updated_date;
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


	/**
	 * query database to get one medication. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_Medication
	 */
	public static function findOne($params = null) {
		$medication = parent::findOneRow($params);
		if ($medication) {
			$unusedMeds = new Application_Model_Medication();
			$unusedMeds->map($medication);
			return $unusedMeds;
		}
		return false;
	}

	public static function findAll($params = array()) {
		$medication = new Application_Model_Medication();
		$select = $medication->select();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value);
			}
		}
		if ($rows = $medication->fetchAll($select)) {
			return $rows->toArray ();
		}
		return false;
	}

	public static function create($data) {
		$key = $this->insert ( array (
				'name' => $data['name'],
				'category' => $data['category'],
				'recovery_id' => $data['recovery_id'],
				'bin_id' => isset($data['bin_id']) ? $data['bin_id'] : null,
				'ndc_number' => isset($data['ndc_number']) ? $data['ndc_number'] : null,
				'rejected' => isset($data['rejected']) ? $data['rejected'] : false,
				'updated_date' => $data['ndc_number'],
		));
		if ($key) {
			return $key;
		} else {
			return false;
		}
	}

	public function edit($data = null) {
		if (!$data)
			return false;
		$where = $this->getAdapter()->quoteInto('medication_id = ?', $this->getRecoveryId());
		try {
			$this->update($data,$where);	
		} 
		catch (Exception $e) {
			error_log(Zend_Debug::dump($e->getMessage()));
		}
	}

	public function remove() {
		if ($this->getMedicationId())
			return $this->delete($this->_name, 'medication_id = '.$this->getMedicationId());

		else
			return false;
	}	
}