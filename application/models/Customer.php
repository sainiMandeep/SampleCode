<?php
class Application_Model_Customer extends Zend_Db_Table_Abstract
{
	protected $_name = 'vwCustomers';
	
	public static function findOne($customerNumber = null) 
	{		
		if (!$customerNumber)
			throw new Zend_Exception("customerNumber is not valid", 1);
		
		$dbAdapter = Zend_Registry::get('dbgp');	

		$select = $dbAdapter->select()
			->from(array('vw' => 'vwCustomers'), 
				array(
					'RTRIM(CustomerNumber) as CustomerNumber',
					'RTRIM(Name) as PracticeName',
					'SUBSTRING(RTRIM(Phone),1,10) as Phone',
					'RTRIM(DoctorName) as Name',
					'RTRIM(FAX) as FAX',
					'RTRIM(STATE) as STATE',
					'RTRIM(Street) As Street',
					'RTRIM(PostalCode) as PostalCode',
					'RTRIM(CITY) as CITY',
					'RTRIM(COUNTRY) as COUNTRY',
					'RTRIM(EMAIL) as EMAIL'
				)
			)
		->join(array('adr' => 'RG_HF_Customer_Address'), 'adr.CUSTNMBR = CustomerNumber', 
				array(
					'ScanDate',
					'RG_StateLicense as StateLicense',
					'CONVERT(VARCHAR(19),StateLicenseExpDate, 101) as ExpirationDate'
				)
			)
		->where('CustomerNumber = ?', $customerNumber)
		->where('ADRSCODE = ?', 'PRIMARY');
		

		$customer = $dbAdapter->fetchRow($select);
		if (!$customer)
			return false;
		return $customer;
	}
}