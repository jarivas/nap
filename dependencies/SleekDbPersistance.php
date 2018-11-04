<?php
use Nap\Response;

class SleekDbPersistance extends Nap\Persistence{
    const CRITERIA_EQUAL = '=';
    const CRITERIA_NOT_EQUAL = '!=';
    const CRITERIA_GREATER_THAN = '>';
    const CRITERIA_GREATER_THAN_EQUAL = '>=';
    const CRITERIA_LESS_THAN = '<';
    const CRITERIA_LESS_THAN_EQUAL = '<=';
    const VALID_CRITERIA = [CRITERIA_EQUAL, CRITERIA_NOT_EQUAL, CRITERIA_GREATER_THAN, CRITERIA_GREATER_THAN_EQUAL,
        CRITERIA_LESS_THAN, CRITERIA_LESS_THAN_EQUAL];
    
    public function __construct($datasetName) {
        $this->dataset = self::$database->store($datasetName);
    }
    
    public static function setDb(array &$db) {
        $path = $db['host'] . DIRECTORY_SEPARATOR . $db['dbName'];
        
        self::$database = new \SleekDB\SleekDB($path);
        
        return SleekDbPersistance;
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