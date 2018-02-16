<?php

class Application_Form_Index_Create extends Zend_Form
{
	protected $serial_number;
    protected $checkin_user_id;
    public $classValid;

    function __construct($serial_number = null) {
        if (!($this->serial_number = $serial_number))
            throw new Zend_Exception("Serial Number is required", 1);

		parent::__construct();
	}

	public function init()
    {
        $this
        	->setMethod('post')
	        ->setAction('/index/create/serial_number/'.$this->serial_number)
	        ->setAttrib('id','form_create')
            ->setDecorators(array(
                    'FormElements',
                    'Form'
            ));
        $this->setElementDecorators( array( 'ViewHelper'));        

        $this->addElement('hidden', 'serial_number');
        $this->addElement('select', 'recovery_type', array(
            'label'=>'Type',
            'multiOptions'=>array(
                '1' => 'Unused Medications',
                '2' => 'AWR',
            ),
            'class' => 'small'
        ));
    }
}