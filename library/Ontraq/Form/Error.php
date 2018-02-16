<?php
class Ontraq_Form_Error {
	public static function renderBox($element) {
		$result = '';
		$messages = $element->getMessages();
		foreach($messages as $message) {
			$result .= '<div class="alert alert-error">'.$message.'</div>';
		}
		return $result;
	}

	public static function render($element) {
		$result = '';
		$messages = $element->getMessages();
		foreach($messages as $message) {
			$result .= '<span class="help-block note"><i class="icon-warning-sign"></i>'.$message.'</span>';
		}
		return $result;
	}
}
