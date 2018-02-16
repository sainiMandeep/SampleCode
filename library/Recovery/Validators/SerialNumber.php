<?php
class Recovery_Validators_SerialNumber extends Zend_Validate_Regex
{  
    const UNIQUE_ID_LENGTH = 8;
    const ITEM_NUMBER_LENGTH = 7;
    const OLD_ITEM_NUMBER_LENGTH = 4;    
    const NOT_MATCH_MESSAGE = 'Please check tracking number entered. It should be the combination of:
								(4 digits)-(8 digits)  OR  (7-digits)-(8 digits).';

    public function __construct()
    {
        parent::__construct('/^(?:\d{'.static::OLD_ITEM_NUMBER_LENGTH.'}|\d{'.static::ITEM_NUMBER_LENGTH.'})-\d{'.static::UNIQUE_ID_LENGTH.'}$/');
        $this->setMessage(static::NOT_MATCH_MESSAGE, static::NOT_MATCH);
    }
}
