<?php

namespace Nap;

class SleekDbPersistance extends Persistence{
    const CRITERIA_EQUAL = '=';
    const CRITERIA_NOT_EQUAL = '!=';
    const CRITERIA_GREATER_THAN = '>';
    const CRITERIA_GREATER_THAN_EQUAL = '>=';
    const CRITERIA_LESS_THAN = '<';
    const CRITERIA_LESS_THAN_EQUAL = '<=';
    const VALID_CRITERIA = [self::CRITERIA_EQUAL, self::CRITERIA_NOT_EQUAL, self::CRITERIA_GREATER_THAN,
    self::CRITERIA_GREATER_THAN_EQUAL, self::CRITERIA_LESS_THAN, self::CRITERIA_LESS_THAN_EQUAL];
    
    public function __construct($datasetName) {
        $this->dataset = self::$database->store($datasetName);
    }
    
    public static function setDb(array &$db) {
        $path = $db['host'] . DIRECTORY_SEPARATOR . $db['dbName'];
        
        self::$database = new \SleekDB\SleekDB($path);
        
        return __CLASS__;
    }
    
    protected function formatCriteria(array &$criteria){
        $dummy = '';
        $condition = '';
        
        foreach($criteria as $fieldName => $value){
            $dummy = json_decode($value);
            
            if(is_array($dummy)){
                $condition = &$dummy['condition'];
                
                if(! in_array($condition, self::VALID_CRITERIA))
                    throw new Exception("Invalid condition in criteria", Response::WARNING_TYPE_BAD_REQUEST);
                
                $this->dataset->where($fieldName, $condition, $dummy['value']);
            } else
                $this->dataset->where($fieldName, self::CRITERIA_EQUAL, $dummy);
        }
    }
    
    public function create(array $item) {
        return $this->dataset->insert($item);
    }

    public function read(array $criteria) {
        $this->formatCriteria($criteria);
        
        return $this->dataset->fetch();
    }

    public function update(array $criteria, array $item) {
        $this->formatCriteria($criteria);
        
        return $this->dataset->update($item);
    }

    public function delete(array $criteria) {
        $this->formatCriteria($criteria);
        
        return $this->dataset->delete();
    }
}