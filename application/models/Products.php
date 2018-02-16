<?php
class Application_Model_Products extends Zend_Db_Table_Abstract
{

    protected $_name = 'recovery_items';
    
    public static function findOne($itemNumber) {
        if(!$itemNumber) {
            throw new Exception("Item number is missing", 1);
        }

        $productsModel = new Application_Model_Products();
        $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $productsModel->select() 
                ->from($productsModel, array(
                    'item_number'=>'RTRIM(item_number)',
                    'category' => 'RTRIM(category)')
                )       
                ->where('legacy_item_number = ?', $itemNumber)
                ->orwhere('item_number =?', $itemNumber) ;
        $row = $db->fetchRow($select);       
        if(!$row) {
            return false;
        }
        return $row;
    }

    public static function getRecoveryType($itemNumber) 
    {
        if (!$itemNumber) {
            throw new Exception("Item number is missing", 1);      
        }
        $itemModel = new Application_Model_Products();
        $item = $itemModel::findOne($itemNumber);
        $recovery_type = '';

        switch ($item['category']) {

            case'unused_meds':
                $recovery_type = 1;
            break;
            case'amalgam':
                $recovery_type = 2;
            break;
            case'mwaste':
                $recovery_type = 3;
            break;
        }
        return $recovery_type;
    }

    
    public static function findAll()
    {
        $productsModel = new Application_Model_Products();
        $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $productsModel->select() 
                ->from($productsModel, array(
                    'name' => 'RTRIM(name)',
                    'processing_cost' => 'CONVERT(MONEY,RTRIM(processing_cost))',
                    'recovery_item_id' => 'recovery_item_id',
                    'category' => 'RTRIM(category)',                    
                    'item_number' => new Zend_Db_Expr("
                        CASE 
                            WHEN legacy_item_number <> null 
                            THEN concat(RTRIM(legacy_item_number), '/', RTRIM(item_number))
                            ELSE
                            RTRIM(item_number)

                        END"  
                        )
                    )
                )
                ->order('category desc')
                ->order('item_number asc');
        $rows = $db->fetchAll($select);        
        if(!$rows) {
            return false;
        }
        return $rows;
    }

    public function save($data) 
    {        
        if (!$data) {
            throw new Exception('Data is missing!');
        }      
        
        $where = array('recovery_item_id = ?' => $data['id']);
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $data = array (
            'processing_cost' => $data['value']
        );
      
        $key = $dbAdapter->update('recovery_items', $data, $where);

        if($key){
            return true;
        }
        return false;        
    }

    public static function productById($id)
    {
        if (!$id) {
            throw new Exception('Id is missing!');
        }  
        $productsModel = new Application_Model_Products();
        $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $productsModel->select() 
            ->from($productsModel, 
                array(
                    'processing_cost' => 'RTRIM(processing_cost)',
                    'item_number' => 'RTRIM(item_number)',
                    'name' => 'RTRIM(name)',
                    'category' => new Zend_Db_Expr("
                        CASE 
                            WHEN category = 'unused_meds'
                            THEN 'Unused Meds Recovery'
                            WHEN category = 'mwaste'
                            THEN 'Sharps'
                            ELSE 'Amalgam'
                        END"  
                    )
                )
            )       
            ->where('recovery_item_id = ?', $id);
        $row = $db->fetchRow($select);       
        if(!$row) {
            return false;
        }
        return $row;
    }
}
