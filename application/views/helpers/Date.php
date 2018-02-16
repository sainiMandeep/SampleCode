<?php
class Zend_View_Helper_Date extends Zend_View_Helper_Abstract{
	public function Date($date = null){
		if (!$date || (strlen($date) < 8))
			return '';
		
		return Ontraq_Date::sqlToJQUERY($date);
	}
}
?>