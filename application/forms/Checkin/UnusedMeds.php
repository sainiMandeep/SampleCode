<?php

class Application_Form_Checkin_UnusedMeds extends Zend_Form
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
	        ->setAction('/checkin/unusedmeds/serial_number/'.$this->serial_number)
	        ->setAttrib('id','form_unusedmeds')
            ->setDecorators(array(
                    'FormElements',
                    'Form'
            ));
        $this->setElementDecorators( array( 'ViewHelper'));        

    	$this->addElement('hidden', 'serial_number');
        $this->addElement('text', 'weight', array(
                'required'=>'true',
        		'validators' => array(
        				array('Float',true, array('min' => 0))
        		),
        		'placeholder' => 'Weight (oz)',
        		'label' => 'Weight (oz)',
                'class' => 'small'
        ));
        $this->addElement('text', 'checkin_date', array(
                'required'=>'true',
                'validators' => array(
                        array('Date',false,array('format' => 'mm/dd/yyyy'))
                ),
                'placeholder' => 'Date Received (mm/dd/yyyy)',
                'label' => 'Date Received',
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
        $this->addElement('checkbox', 'onhold');
    }

    public function isValid($data) {
        $valid = parent::isValid($data);        

        $checkin_date = new Ontraq_Date();
        $checkin_date->setJQUERY($this->checkin_date->getValue());
        if ($checkin_date > Ontraq_Date::now()) {
            $valid = false;
            $this->checkin_date->addError('The date cannot be set in the future. Date format is mm/dd/yyyy');
        }

        if ($valid)
            $this->classValid = 'valid';
        else
            $this->classValid = 'error';

        if ($this->weight->getValue())
            $this->weight->setValue(round($this->weight->getValue(), 2));

        return $valid;
    }

    public function mapModel() {
        $data = array();
        $data['checkin_date'] = Ontraq_Date::JQUERYToSQL($this->checkin_date->getValue());


        if ($this->checkin_notes->getValue())
            $data['checkin_notes'] = $this->checkin_notes->getValue();
        else
            $data['checkin_notes'] = null;
        if ($this->general_notes->getValue())
            $data['general_notes'] = $this->general_notes->getValue();
        else
            $data['general_notes'] = null;
        if ($this->weight->getValue())
            $data['weight'] = $this->weight->getValue();
        else
            $data['weight'] = null;
        return $data;
    }
}


