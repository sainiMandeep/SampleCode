<?php
class Recovery_Validators_Weight extends Zend_Validate_Abstract 
{
    const INVALID = 'digitsInvalid';
  
    protected $_messageTemplates = array(
        self::INVALID => "Please check entered weight.",
      );
  
    public function isValid($value)
    {
        //weight can be 0-9 with or without decimal 
        $isValid = true;        
        if (!is_numeric($value)) {
            $this->_error(self::INVALID);
            $isValid= false;
        }
        return $isValid;
    }
}

