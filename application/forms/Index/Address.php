<?php

class Application_Form_Index_Address extends Zend_Form
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
	        ->setAction('/index/address/serial_number/'.$this->serial_number)
	        ->setAttrib('id','form_address')
            ->setDecorators(array(
                    'FormElements',
                    'Form'
            ));
        $this->setElementDecorators( array( 'ViewHelper'));        

        $this->addElement('hidden', 'serial_number');
    	$this->addElement('hidden', 'recovery_type');

        $this->addElement('select', 'recovery_type', array(
            'label'=>'Type',
            'multiOptions'=>array(
                '1' => 'Unused Medications',
                '2' => 'AWR',
            ),
            'class' => 'small'
        ));
        $this->addElement('text', 'street', array(
                'required'=>'true',
        		'placeholder' => 'Address',
                'validators' => array(
                        array('StringLength',true, array('max' => 100 , 'min' => 2))
                ),
        		'label' => 'Address',
                'class' => 'small'
        ));
        $this->addElement('text', 'zipcode', array(
                'placeholder' => 'Zip Code',
                'validators' => array(
                        array('StringLength',true, array('max' => 20 , 'min' => 1))
                ),
                'label' => 'Zip Code',
                'class' => 'small'
        ));
        $this->addElement('text', 'city', array(
                'placeholder' => 'City',
                'validators' => array(
                        array('StringLength',true, array('max' => 100 , 'min' => 2))
                ),
                'label' => 'City',
                'class' => 'small'
        ));
        $this->addElement('text', 'state', array(
                'placeholder' => 'State',
                'validators' => array(
                        array('StringLength',true, array('max' => 2    , 'min' => 2))
                ),
                'label' => 'State',
                'class' => 'small'
        ));
    }
}