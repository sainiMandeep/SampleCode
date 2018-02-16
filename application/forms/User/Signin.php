<?php
class Application_Form_User_Signin extends Zend_Form {
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod ( 'post' )->setAction ( '/index/index' )->setAttrib ( 'id', 'form_signin' );
		$this->setDecorators ( array (
				'FormElements',
				'Form'
		) );
		$this->setElementDecorators ( array (
				'ViewHelper',
				'errors'
		) );
		$this->addElement(
		 		'password', 'password', array(
				'required'   => true,
				'validators' => array(
					array('StringLength',true, array('max' => 20 , 'min' => 2))
				),
				'placeholder' => 'Password',
				'label' => 'Password',
		 ));

	}
}
?>