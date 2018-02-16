<?php
/*
*/
class Ontraq_Date extends Zend_Date
{
	const MYSQL_FORMAT = 'yyyy-MM-dd';
	const MYSQL_DATETIME_FORMAT = 'yyyy-MM-dd hh:mm:ss';
	const JQUERY_FORMAT = 'MM/dd/yyyy';

	public function setJQUERY($date) {
        return parent::set($date,self::JQUERY_FORMAT);
    }

    public function setSQL($date) {
        if (!$date)
            $date = '0000-0000 00:00:00';
        return parent::set($date,self::MYSQL_FORMAT);
    }

    public function setSQLDatetime($date) {
        return parent::set($date,self::MYSQL_DATETIME_FORMAT);
    }
    
    public function getJQUERY() {
        return parent::get(self::JQUERY_FORMAT);
    }

    public function getSQL() {
       return parent::get(self::MYSQL_FORMAT);
    }
    
    public function getSQLDatetime() {
       return parent::get(self::MYSQL_DATETIME_FORMAT);
    }
    
    public function setNow() {
        $now = date('Y-m-d');
        $this->setSQL($now);
    }
    
    public function laterThan($date) {
        $datetime1 = new DateTime($this->getSQL());
        $datetime2 = new DateTime($date->getSQL());
        return ($datetime1 > $datetime2);
    }
    
    public function equal($date) {
        $datetime1 = new DateTime($this->getSQL());
        $datetime2 = new DateTime($date->getSQL());
        return ($datetime1 == $datetime2);
    }

    public static function sqlToJQUERY($date) {
        $newDate = new Ontraq_Date();
        $newDate->setSQL($date);
        return $newDate->getJQUERY();
        // $date = explode(' ', $date);
        // $date = explode('-', $date[0]);
        // return $date[1].'/'.$date[2].'/'.$date[0];
    }

    public static function JQUERYToSQL($date) {
        $newDate = new Ontraq_Date();
        $newDate->setJQUERY($date);
        return $newDate->getSQL();
        // $date = explode('/', $date);
        // return $date[2].'-'.$date[0].'-'.$date[1];
    }

    public static function now($format = null , $add = null) {
        $date = new Ontraq_Date();
        $date->setNow();
        if ($add)
            $date->add($add, Zend_Date::DAY);
        return self::format($date , $format);
    }

    public static function format($date , $format = null) {
       if (!$format)
            return $date;
        elseif ($format == 'SQL') 
            return $date->getSQL();
        elseif ($format == 'JQUERY')
            return $date->getJQUERY();

        return $date;
    }
    
}
?>