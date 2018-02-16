<?php
class Zend_View_Helper_FormErrorHeader extends Zend_View_Helper_Abstract{
	public function FormErrorHeader($form){
		$messages = $form->getMessages();
		if (count($messages) == 0) return ''; 
		if (count($messages) == 1) 
			$message = 'Oops...there is one error.';
		else { //multiple errors
			$message = 'Oops...There are errors.';
		}

		foreach ($messages as $key => $msg) {
			if (is_int($key))	
				$message .= '<br>'.$msg;
		}	

		return '<div class="alert alert-error">'.$message.'</div>';
	}
}
?>