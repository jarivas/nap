<?php

namespace Nap;

class SleekDbPersistance extends Persistence {

    protected $path;

    public static function setDb(array &$db): string {
        $path = $db['host'] . DIRECTORY_SEPARATOR . $db['dbName'];

        self::$database = new \SleekDB\SleekDB($path);

        return __CLASS__;
    }

    public function __construct($datasetName) {
        $this->dataset = self::$database->store($datasetName, self::$database->dataDirectory);
    }

    public function create(array $item): bool {
        $result = $this->dataset->insert($item);
        
        return ($result) ? true : false;
    }

    public function read(array $criteria, array $options = []): array {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, Persistence::CRITERIA_EQUAL, $value);
        
        if(isset($options['limit']) && is_int($options['limit']))
            $this->dataset->limit($options['limit']);
        
        if(isset($options['skip']) && is_int($options['skip']))
            $this->dataset->skip($options['skip']);
        
        if(isset($options['orderBy']) && is_array($options['orderBy'])){
            $o = $options['orderBy'];
            $this->dataset->orderBy($o['order'], $o['field']);
        }

        return $this->dataset->fetch();
    }

    public function update(array $criteria, array $item): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, Persistence::CRITERIA_EQUAL, $value);

        return $this->dataset->update($item);
    }

    public function delete(array $criteria): bool {
        foreach ($criteria as $fieldName => $value)
            $this->dataset->where($fieldName, Persistence::CRITERIA_EQUAL, $value);

        return $this->dataset->delete();
    }

}
