<?php

class Application_Form_Employee_Create extends Zend_Form
{
	
	public function init()
	{
		// Set the method for the display form to POST
		$this
		->setMethod('post')
		->setAction('/employee/create')
		->setAttrib('id','form_createnewemployee');
		$this->setDecorators(array(
				'FormElements',
				'Form'
		));
		 $this->setElementDecorators( array( 'ViewHelper'));
		// Add a firstname
		$this->addElement('text', 'firstname', array(
				'required'   => true,
				'validators' => array(
						array('StringLength',true, array('max' => 50 , 'min' => 2))
				),
				'placeholder' => 'First Name',
				'label' => 'First Name'
		));

		
		// Add a lastname
		$this->addElement('text', 'lastname', array(
				'required'   => true,
				'validators' => array(
						array('StringLength',true, array('max' => 50 , 'min' => 2))
				),
				'placeholder' => 'Last Name',
				'label' => 'Last Name'
		));
 
		
	}
}
?>