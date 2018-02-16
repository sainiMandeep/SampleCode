<?php
class Application_Form_Bin_EditLocation extends Zend_Form {
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod ( 'post' )->setAction ( '/bin/editlocation' )->setAttrib ( 'id', 'form_editlocation' );
		$this->setDecorators ( array (
				'FormElements',
				'Form' 
		) );
		$this->setElementDecorators ( array (
				'ViewHelper' 
		) );
		$this->addElement('hidden', 'token');		
	  $this->addElement(
				'text', 'binlocation', array(						
				'validators' => array(
						array('StringLength',true, array('max' => 50 , 'min' => 2))
				),
				'placeholder' => 'Location of Bin',
				'label' => 'Bin Location',
				'data-provide' => "typeahead"
		));
	}
}
?>