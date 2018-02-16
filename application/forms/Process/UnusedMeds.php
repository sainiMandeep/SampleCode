<?php

class Application_Form_Process_UnusedMeds extends Zend_Form
{
	protected $serial_number;
    protected $processed_user_id;
    protected $medicationsArray;
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
	        ->setAction('/process/unusedmeds/serial_number/'.$this->serial_number)
	        ->setAttrib('id','form_unusedmeds')
            ->setDecorators(array(
                    'FormElements',
                    'Form'
            ));
        $this->setElementDecorators( array( 'ViewHelper'));        
        $this->addElement('text', 'weight', array(
                'required'=>'true',
                'validators' => array(
                        array('Float',true, array('min' => 0))
                ),
                'placeholder' => 'Weight (oz)',
                'label' => 'Weight (oz)',
                'class' => 'small'
        ));
    	$this->addElement('hidden', 'serial_number');
        $this->addElement('hidden', 'medications');
        $this->addElement('text', 'processed_date', array(
                'required'=>'true',
                'validators' => array(
                        array('Date',false,array('format' => 'mm/dd/yyyy'))
                ),
                'placeholder' => 'Date Processed (mm/dd/yyyy)',
                'label' => 'Date Processed',
                'class' => 'small',
                'value' => Ontraq_Date::now('JQUERY')
        ));
        $this->addElement('textarea', 'checkin_notes', array(
                'validators' => array(
                        array('StringLength',true, array('max' => 500)),
                ),
                'placeholder' => 'Notes to customer',
                'label' => 'Notes to customer'
        ));
        $this->addElement('textarea', 'general_notes', array(
                'validators' => array(
                        array('StringLength',true, array('max' => 500)),
                ),
                'placeholder' => 'General Notes',
                'label' => 'General Notes'
        ));
        $this->addElement('checkbox', 'sendnotes');
    }

    public function isValid($data) {
        $valid = parent::isValid($data);

        $date = new Ontraq_Date();
        $date->setJQUERY($this->processed_date->getValue());
        if ($date > Ontraq_Date::now()) {
            $valid = false;
            $this->processed_date->addError('The date cannot be set in the future. Date format is mm/dd/yyyy');
        }

        if ($valid)
            $this->classValid = 'valid';
        else
            $this->classValid = 'error';

        return $valid;
    }

    public function mapModel() {
        $data = array();
        $data['processed_date'] = Ontraq_Date::JQUERYToSQL($this->processed_date->getValue());
        $data['weight'] = $this->weight->getValue();
        if ($this->checkin_notes->getValue())
            $data['checkin_notes'] = $this->checkin_notes->getValue();
        else
            $data['checkin_notes'] = null;
        
        if ($this->general_notes->getValue())
            $data['general_notes'] = $this->general_notes->getValue();
        else
            $data['general_notes'] = null;

        return $data;
    }
}