<?php
class Application_Model_Recovery extends Zend_Db_Table_Abstract
{
	protected $_name = 'recovery';
	protected $_primary = 'recovery_id';

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
	* Get recovery_type
	* @return recovery_type
	*/
	public function getRecoveryType() {
		if (isset($this->recovery_type))
			return $this->recovery_type;
		return false;
	}
	
	/**
	* Set recovery_type
	* @param recovery_type
	*/
	public function setRecoveryType($recovery_type) {
		$this->recovery_type = $recovery_type;
	}

	/**
	* Get customer_number
	* @return customer_number
	*/
	public function getCustomerNumber() {
		if (isset($this->customer_number))
			return $this->customer_number;
		return false;
	}
	
	/**
	* Set customer_number
	* @param customer_number
	*/
	public function setCustomerNumber($customer_number) {
		$this->customer_number = $customer_number;
	}

	/**
	* Get serial_number
	* @return serial_number
	*/
	public function getSerialNumber() {
		if (isset($this->serial_number))
			return $this->serial_number;
		return false;
	}
	
	/**
	* Set serial_number
	* @param serial_number
	*/
	public function setSerialNumber($serial_number) {
		$this->serial_number = $serial_number;
	}
	/**
	* Get status
	* @return status
	*/
	public function getStatus() {
		if (isset($this->status))
			return $this->status;
		return false;
	}
	
	/**
	* Set status
	* @param status
	*/
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	* Get weight
	* @return weight
	*/
	public function getWeight() {
		if (isset($this->weight))
			return $this->weight;
		return false;
	}
	
	/**
	* Set weight
	* @param weight
	*/
	public function setWeight($weight) {
		$this->weight = $weight;
	}

	/**
	* Get shippedDate
	* @return shippedDate
	*/
	public function getShippedDate() {
		if (isset($this->shippedDate))
			return $this->shippedDate;
		return false;
	}
	
	/**
	* Set shippedDate
	* @param shippedDate
	*/
	public function setShippedDate($shippedDate) {
		$this->shippedDate = $shippedDate;
	}

	/**
	* Get checkin_date
	* @return checkin_date
	*/
	public function getCheckinDate() {
		if (isset($this->checkin_date))
			return $this->checkin_date;
		return false;
	}
	
	/**
	* Set checkin_date
	* @param checkin_date
	*/
	public function setCheckinDate($checkin_date) {
		$this->checkin_date = $checkin_date;
	}

	/**
	* Get checkin_notes
	* @return checkin_notes
	*/
	public function getCheckinNotes() {
		if (isset($this->checkin_notes))
			return $this->checkin_notes;
		return false;
	}
	
	/**
	* Set checkin_notes
	* @param checkin_notes
	*/
	public function setCheckinNotes($checkin_notes) {
		$this->checkin_notes = $checkin_notes;
	}


	/**
	* Get checkin_user_id
	* @return checkin_user_id
	*/
	public function getCheckinUserId() {
		if (isset($this->checkin_user_id))
			return $this->checkin_user_id;
		return false;
	}
	
	/**
	* Set checkin_user_id
	* @param checkin_user_id
	*/
	public function setCheckinUserId($checkin_user_id) {
		$this->checkin_user_id = $checkin_user_id;
	}

	/**
	* Get processed_date
	* @return processed_date
	*/
	public function getProcessedDate() {
		if (isset($this->processed_date))
			return $this->processed_date;
		return false;
	}
	
	/**
	* Set processed_date
	* @param processed_date
	*/
	public function setProcessedDate($processed_date) {
		$this->processed_date = $processed_date;
	}

	/**
	* Get processed_notes
	* @return processed_notes
	*/
	public function getProcessedNotes() {
		if (isset($this->processed_notes))
			return $this->processed_notes;
		return false;
	}
	
	/**
	* Set processed_notes
	* @param processed_notes
	*/
	public function setProcessedNotes($processed_notes) {
		$this->processed_notes = $processed_notes;
	}

	/**
	* Get processed_user_id
	* @return processed_user_id
	*/
	public function getProcessedUserId() {
		if (isset($this->processed_user_id))
			return $this->processed_user_id;
		return false;
	}
	
	/**
	* Set processed_user_id
	* @param processed_user_id
	*/
	public function setProcessedUserId($processed_user_id) {
		$this->processed_user_id = $processed_user_id;
	}

	/**
	* Get general_notes
	* @return general_notes
	*/
	public function getGeneralNotes() {
		if (isset($this->general_notes))
			return $this->general_notes;
		return false;
	}

	/**
	* Set general_notes
	* @param general_notes
	*/
	public function setGeneralNotes($general_notes) {
		$this->general_notes = $general_notes;
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
	* Get item_number
	* @return item_number
	*/
	public function getItemNumber() {
		if (isset($this->item_number))
			return $this->item_number;
		return false;
	}
	
	/**
	* Set item_number
	* @param item_number
	*/
	public function setItemNumber($item_number) {
		$this->item_number = $item_number;
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

	/**
	 * query database to get one recovery. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_Recovery
	 */
	public static function findOneRow($params = null) {		
		if (!$params)
			throw new Zend_Exception("Params is requried", 1);
		//@todo : add order by date
		$recovery = new Application_Model_Recovery();
		
		$select = $recovery->select();
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value);
			}
		}		
		if (!is_array($params)) {
			$select->where('recovery_id = ?', $params);
		}		
		$select->order('shipped_date desc');
		try {
			$row = $recovery->fetchRow($select);
			if(!$row) {
				return false;
			}
			return $row->toArray();					
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 1);            
		}
	}

	/**
	 * query database to get one recovery. Return false otherwise
	 * @param  array $params
	 * @return Application_Model_Recovery
	 */
	public static function findOne($params = null) {
		$recovery = self::findOneRow($params);	
		if ($recovery) {
			
			switch ($recovery['recovery_type']) {
				case 1:
					$recoveryModel = new Application_Model_UnusedMeds();
					break;
				case 2:
					$recoveryModel = new Application_Model_Awr();
					break;
				default:
					$recoveryModel = new Application_Model_Recovery();
					break;
			}
			$recoveryModel->map($recovery);
			return $recoveryModel;
		}
		return false;
	}

	public static function findAll($params = array(),$limit = null, $order = null) {
		$recovery = new Application_Model_Recovery();
		$select = $recovery->select();
		$select->from($recovery->_name);
		$select->setIntegrityCheck(false);
		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$select->where($param.' = ?', $value)
				->joinLeft('employee','employee.employee_id = checkin_user_id');
			}
		}

		if ($limit)
			$select->limit($limit);
		if ($order)
			$select->order($order);
		// throw new Exception($select->__toString(), 1);
		
		if ($rows = $recovery->fetchAll($select)) {
			return $rows->toArray();
		}
		return false;
	}

	public function map($data) {	
		$this->setRecoveryId($data['recovery_id']);
		$this->setRecoveryType($data['recovery_type']);
		$this->setStatus($data['status']);
		$this->setItemNumber($data['item_number']);
		$this->setSerialNumber($data['serial_number']);
		$this->setCustomerNumber($data['customer_number']);
		$this->setWeight($data['weight']);
		$this->setShippedDate($data['shipped_date']);
		$this->setCheckinDate($data['checkin_date']);
		$this->setCheckinNotes($data['checkin_notes']);
		$this->setCheckinUserId($data['checkin_user_id']);
		$this->setProcessedDate($data['processed_date']);
		$this->setProcessedNotes($data['processed_notes']);
		$this->setProcessedUserId($data['processed_user_id']);
		$this->setGeneralNotes($data['general_notes']);
		$this->setUpdatedDate($data['updated_date']);
		$this->setCreatedDate($data['created_date']);
	}

	/**
	 * Return name of the recovery
	 * @param  int $type
	 * @return string name of the recovery type
	 */
	public static function mapType($type = null) {
		if (!$type)
			throw new Zend_Exception("Type is required", 1);

		$data = array(
			1 => 'unusedmeds'
		);

		if (!isset($data[$type]))
			throw new Zend_Exception("Type is invalid", 1);		

		return $data[$type];
	}


	/**
	 * Return full name of the recovery
	 * @param  int $type
	 * @return string name of the recovery type
	 */
	public static function mapNameType($type = null) {
		if (!$type)
			throw new Zend_Exception("Type is required", 1);

		$data = array(
			1 => 'Unused Meds',
			2 => 'AWR',
			3 => 'Sharps'
		);

		if (!isset($data[$type]))
			throw new Zend_Exception("Type is invalid", 1);		

		return $data[$type];
	}

	public static function mapStatus($status = null) {
		if (!$status)
			throw new Zend_Exception("Status is required", 1);

		$data = array(
			1 => 'Shipped',
			2 => 'Sent',
			3 => 'Checked in',
			4 => 'Processed',
			5 => 'Rejected',
			6 => 'In Bin',
			7 => 'In Bin (partially rejected)',
			8 => 'Destroyed',
			9 => 'On Hold'				
		);

		if (!isset($data[$status]))
			throw new Zend_Exception("Status is invalid", 1);		

		return $data[$status];
	}

	public function getUnprocessedStatus() {
		return 2;
	}

	public function create($data) {
		$key = $this->insert ( $data );
		if ($key) {
			return $key;
		} else {
			return false;
		}
	}

	public static function getMWSCheckedInRecovery() 
	{
		$recovery = new Application_Model_Recovery();

		$select = $recovery->select()		
			->from($recovery->_name, array(
				'serial_number',
				'CONVERT(VARCHAR(19),checkin_date, 101) as checkin_date',
				'recovery_id', 
				'weight',
				'recovery_type' => new Zend_Db_Expr(
                    "
                        CASE
                            WHEN recovery_type = 1
                            THEN 'Pharmaceutical'
                            WHEN recovery_type = 2
                            THEN 'Amalgam'
                            WHEN recovery_type = 3
                            THEN 'Sharps'
                            ELSE 'N/A'
                        END
                    "
                )))			
			->where('processed_date is null')
			->where('checkin_date is not null')
			->where('vendor = ?', 'MWS' );	
		if ($rows = $recovery->fetchAll($select)) {			
			return $rows->toArray();
		}
		return false;
	}	

	public function mwsReport($startDate, $endDate)
	{
		if(!$startDate || !$endDate) {
            throw new Exception('No date range is selected.');
        }
        $dbAdapter = $this->getAdapter();
        
        $stmt=$dbAdapter->prepare("exec MWS_Report 
                @startDate=?,
                @endDate=? 
            ");
        $stmt->bindParam(1, $startDate, PDO::PARAM_STR);
        $stmt->bindParam(2, $endDate, PDO::PARAM_STR);           
        try {                      
            $stmt->execute();
            $rows = $stmt->fetchAll(); 
            return $rows;
        } catch(Exception $e){
             throw new Exception($e->getMessage(), 1);
             return false;
        }
	}

	public function getDetailedReport($startDate, $endDate)
	{
		if(!$startDate || !$endDate) {
            throw new Exception('No date range is selected.');
        }
        $dbAdapter = $this->getAdapter();
        $select = $dbAdapter->select()
        	->from(array('r' =>'recovery'), array(
        		'item_number' => 'rtrim(r.item_number)',
        		'weight' => 'rtrim(weight)',
        		'general_notes' => 'rtrim(general_notes)',
        		'processed_date' =>'CONVERT(VARCHAR(19), processed_date, 101)'
        		                
        	))
        	->join(array('i' => 'recovery_items'), 'r.item_number = i.item_number', array('name' => 'rtrim(name)') )
        	->where("processed_date between '".$startDate."' and '".$endDate."'")
        	->where('vendor = ?', 'MWS' )
        	->order('r.item_number desc')
        	->order('weight asc');        
    	$rows = $dbAdapter->query($select)->fetchAll();
		return $rows;
	}
}