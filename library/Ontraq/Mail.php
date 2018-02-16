<?php
class Ontraq_Mail extends Zend_Mail
{
	public function send( Zend_Mail_Transport_Abstract $transport = null ) {
		if (defined('NOMAIL') && (NOMAIL === true))
			return true;

		return parent::send($transport);
	}

	public function setSubject($subject) {
		if (defined('EMAIL_PREFIX_SUBJECT')) {
			$subject = EMAIL_PREFIX_SUBJECT.$subject;
		}
		return parent::setSubject($subject);	
	}

	public static function sendMail($params) {
		if (defined('NOMAIL') && (NOMAIL === true))
			return true;

		if (!(isset($params['to']) && isset($params['body']) && isset($params['subject']))) {
			return false;
		}

		$config = array(
			'auth'=>SES_AUTH,
			'ssl'=>SES_SSL,
			'port'=>SES_PORT,
			'username'=>SES_USERNAME,
			'password'=>SES_PASSWORD
			);
		$zend_mail = new Ontraq_Mail();

		$tr = new Zend_Mail_Transport_Smtp(SES_HOST, $config);
		Ontraq_Mail::setDefaultFrom(CSR_EMAIL);
		Ontraq_Mail::setDefaultReplyTo(CSR_EMAIL);
		$zend_mail->addTo($params['to']);
		if(isset($params['bcc']))
			$zend_mail->addBcc($params['bcc']);
		$zend_mail->setBodyHtml($params['body']);
		$zend_mail->setSubject($params['subject']);

		try {
			$zend_mail->send($tr);
		}
		catch (Exception $e) {
			echo 'An error has occured: '.$e;
		}
	}

	/* public static function getSendGeneralNotes($params){
		$emailBody = '<html><body><h1>Message sent during '.$params['process'].'</h1>';
		$emailBody .= 'Customer Number: '.$params['customer_number'].'<br>';
		$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
		$emailBody .= 'Notes: <br>';
		$emailBody .= $params['notes'];
		$emailBody .='</body></html>';
		return $emailBody;
	} */

	public static function sendGeneralNotes($params) {
		$email = self::getTo();
		$bcc = self::getBcc();

		$emailBody = '<html><body><h1>Message sent during '.$params['process'].'</h1>';
		$emailBody .= 'Customer Number: '.$params['customer_number'].'<br>';
		$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
		$emailBody .= 'Notes: <br>';
		$emailBody .= $params['notes'];
		$emailBody .='</body></html>';

		self::sendMail(array(
            'to' => $email,
            'bcc' => $bcc,
            'subject' => 'General Notes from Rebec/Pristine',
            'body' => $emailBody
            )
        );
		return true;
	}

		public static function sendGeneralNotesAWR($params) {
			$email = self::getTo();
			$bcc = self::getBcc();

			$emailBody = '<html><body><h1>Message sent during AWR '.$params['process'].'</h1>';
			$emailBody .= 'Customer Number: '.$params['customer_number'].'<br>';
			$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
			$emailBody .= 'Weight: '.$params['weight'].'<br>';
			$emailBody .= 'Employee: '.$params['employee'].'<br>';
			$emailBody .= 'Notes: <br>';
			$emailBody .= $params['notes'];
			$emailBody .='</body></html>';

			self::sendMail(array(
	            'to' => $email,
	            'bcc' => $bcc,
	            'subject' => 'General Notes from Rebec/Pristine about AWR',
	            'body' => $emailBody
	            )
	        );
			return true;
		}

	public static function sendRejected($params) {
		$email = self::getTo();
		$bcc = self::getBcc();
		switch ($params['type']) {
		 	case 1:
		 		$type = 'Unused Medications';
		 		break;
		 	case 2:
		 		$type = 'AWR';
		 		break;
		 	
		 	default:
		 		$type = 'Item';
		 		break;
		 } 

		$emailBody = '<html><body><h1>Message sent during '.$params['process'].'</h1>';
		$emailBody .= 'Customer Number: '.$params['customer_number'].'<br>';
		$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
		$emailBody .= 'Item Type: '.$type.'<br>';
		$emailBody .= 'Notes: <br>';
		$emailBody .= $params['notes'];
		$emailBody .='</body></html>';

		self::sendMail(array(
            'to' => $email,
            'bcc' => $bcc,
            'subject' => $type.' rejected from Rebec/Pristine',
            'body' => $emailBody
            )
        );
		return true;
	}

	public static function sendPartiallyRejected($params) {
		$email = self::getTo();
		$bcc = self::getBcc();

		$emailBody = '<html><body><h1>Message sent during '.$params['process'].'</h1>';
		$emailBody .= 'Customer Number: '.$params['customer_number'].'<br>';
		$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
		$emailBody .= 'Notes: <br>';
		$emailBody .= $params['notes'];
		$emailBody .='</body></html>';
		self::sendMail(array(
            'to' => $email,
            'bcc' => $bcc,
            'subject' => 'Bag partially rejected from Rebec/Pristine',
            'body' => $emailBody
            )
        );
		return true;
	}

	public static function sendNotRecognizedItem($params) {
		$email = 'dev.team@healthfirst.com';

		$emailBody = '<html><body><h1>Item not recognized</h1>';
		$emailBody .= 'Type: '.$params['recovery_type'].'<br>';
		$emailBody .= 'Serial Number: '.$params['serial_number'].'<br>';
		$emailBody .= 'Address: '.$params['street'].'<br>';
		$emailBody .= 'Zip Code: '.$params['zipcode'].'<br>';
		$emailBody .= 'City: '.$params['city'].'<br>';
		$emailBody .= 'State: '.$params['state'];
		$emailBody .='</body></html>';
		self::sendMail(array(
            'to' => $email,
            'subject' => 'Pristine : Item not recognized',
            'body' => $emailBody
            )
        );
		return true;	
	}


	public static function getTo() {
		if (APP_ENV === 'PRODUCTION')
		    return 'complianceteam@healthfirst.com';
		else
		    return DEV_EMAIL;
	}

	public static function getBcc() {
		if (APP_ENV === 'PRODUCTION')
		    return 'ontraq@healthfirst.com';
		else
		    return null;
	}

	public static function sendMwsPriceChange($params)
	{
		$email = MARKETING_EMAIL;
		$bcc = DEV_EMAIL;
		$emailBody = '<html><body><h2>MWS Recovery Portal Price Change</h2>';
		$emailBody .= 'Product: '.$params['name'].'<br>';
		$emailBody .= 'Item Number: '.$params['item_number'].'<br>';		
		$emailBody .= 'Category: '.$params['category'].'<br>';
		$emailBody .= 'Price changed from <b>$'.$params['costBeforeChange'].'</b> to <b>$'.$params['processing_cost'].'</b><br>';
		$emailBody .='</body></html>';
		self::sendMail(array(
            'to' => $email,
            'bcc' => $bcc,
            'subject' => 'MWS Price Change',
            'body' => $emailBody
            )
        );
		return true; 
	}
}
