<?php
class User {
	public static function setVendor() {
		$_SERVER['SERVER_ADDR'] = '127.0.0.1';	
	}

	public static function setCustomer() {
		$_SERVER['SERVER_ADDR'] = '128.0.0.1';
	}
}