<?php
class UnusedMeds {
	public static function generate($params = null) {
		// Default
		$status = 1;
		$recovery_type = 1;
		if ($params) {
			if (isset($params['status']))
				$status = $params['status'];
			if (isset($params['recovery_type']))
				$recovery_type = $params['recovery_type'];
		}
		$customer_number = 'TST9998';
		$UnusedMeds = new Application_Model_UnusedMeds();
		$where = $UnusedMeds->getAdapter()->quoteInto('customer_number = ?',$customer_number);
		$UnusedMeds->delete($where);
		$data = array(
			'recovery_type' => $recovery_type,
			'serial_number' => 'UNIT',
			'status'  => $status,
			'shipped_date'  => '11-08-2013',
			'customer_number'  => 'TST9998 ',
			'updated_date'  => Ontraq_Date::now('SQL')
		);
		if ($UnusedMeds->insert($data)) {
			return $data;
		}
	}

	public static function checkinForm($params = null) {
		$checkin_user_code = 'AAAA';
		if ($params) {
			if (isset($params['checkin_user_code']))
				$checkin_user_code = $params['checkin_user_code'];
		}

		$data = array(
			'serial_number' => 'UNIT',
			'weight'  => '1.23',
			'checkin_date'  => '11/13/2013',
			'checkin_notes'  => 'It was a good checkin...I feel awesome now',
			'general_notes'  => 'This is for you customer!! I have just checked in!!',
			'checkin_user_code'  => $checkin_user_code,
			'updated_date'  => Ontraq_Date::now('SQL')
		);
		return $data;
	}

	public static function processForm($params = null) {
		$process_user_code = 'AAAA';
		if ($params) {
			if (isset($params['processed_user_code']))
				$process_user_code = $params['processed_user_code'];
		}

		$data = array(
			'serial_number' => 'UNIT',
			'processed_date'  => '11/13/2013',
			'checkin_notes'  => 'It was a good checkin...I feel awesome now',
			'general_notes'  => 'This is for you customer!! I have just checked in!!',
			'processed_user_code'  => $process_user_code,
			'medications' => '[{"name":"Aspirin","ndc_number":"1111-1111-11","category":"Meds","quantity":2,"rejected":false},{"name":"Biafin","ndc_number":"2222-2222-22","category":"Heat","quantity":3,"rejected":true},{"name":"Biafin2","ndc_number":"2222-2222-22","category":"Heat","quantity":1,"rejected":false},{"name":"Aspirin","ndc_number":"1111-1111-11","category":"Meds","quantity":1,"rejected":false}]',
			'updated_date'  => Ontraq_Date::now('SQL')
		);
		return $data;
	}
}