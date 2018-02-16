<?php
class Application_Form_Bin_Create extends Zend_Form {
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod ( 'post' )->setAction ( '/bin/create' )->setAttrib ( 'id', 'form_newbin' );
		$this->setDecorators ( array (
				'FormElements',
				'Form' 
		) );
		$this->setElementDecorators ( array (
				'ViewHelper' 
		) );
		$wastetype = array(
			0 => 'Select'		
		);
		$wastetype = array_merge($wastetype,Application_Model_Bin::getMapWasteType());		
		$this->addElement ( 
				'select', 'wastetype', array (
				'label' => 	'Waste Type',
				'placeholder'=>'Select',
				'required'=>true,							
				'multiOptions' => $wastetype,
				'validators' => array (array ('StringLength',true)) 
		) );
		$bintype = array(
				0=>'Select'
		);
		$bintype = array_merge($bintype,Application_Model_Bin::getMapBinType());
		$this->addElement (
				'select', 'bintype', array (
						'label' => 	'Bin Type',
						'required'=>true,
						'placeholder'=>'Select',
						'multiOptions' => $bintype,
						'validators' => array (array ('StringLength',true)
						)
		));
		$this->addElement(
				'text', 'binlocation', array(
				'required'   => true,
				'validators' => array(
						array('StringLength',true, array('max' => 50 , 'min' => 2))
				),
				'placeholder' => 'Location of Bin',
				'value'=>'Cage',						
				'label' => 'Bin Location',
				'data-provide' => "typeahead"
		)); 
		$this->addElement(
		 		'text', 'number_id', array(
		 				'required'   => true,
		 				'validators' => array(
		 						array('StringLength',true, array('max' => 20 , 'min' => 2))
		 				),
		 				'placeholder' => 'ID Number',
		 				'label' => 'ID Number',
		 ));

	}

	public function isValid($data) {
	    $valid = parent::isValid($data);
	    // if bin name already exist
	    if ($bin = Application_Model_Bin::findOne(array('number_id' => $this->number_id->getValue()))) {
	        $this->number_id->addError('This ID Number already exists.');
	        $valid = false;
	    }
	    return $valid;
	}
}
?>