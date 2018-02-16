<?php
class Recovery_Validators_Items extends Zend_Validate_Abstract
{
	const MSG_INVALID = 'itemNumberInvalid';    
    const ITEM_NUMBER_LENGTH = 7;
    const OLD_ITEM_NUMBER_LENGTH = 4;

    const VALID_ITEM_NUMBER_LENGTHS = [
        self::OLD_ITEM_NUMBER_LENGTH, 
        self::ITEM_NUMBER_LENGTH
    ];
	protected $_messageTemplates = array(
        self::MSG_INVALID => 'Please check entered item number in serial number.'      
    );

    public function isValid($value)
    {
        $this->_setValue($value); 
        $isValid = true;
       
        if ($value) {  
            $item = Application_Model_Products::findOne($value);
            if(!$item) {               
                $this->_error(self::MSG_INVALID);
                $isValid = false;
            }            
        }
         
        $itemNumberLength = strlen($value);
        if ($itemNumberLength !== null && !in_array($itemNumberLength, static::VALID_ITEM_NUMBER_LENGTHS)) {            
            $this->_error(self::MSG_INVALID);
            $isValid = false;
        }                   
        return $isValid;
    }
}