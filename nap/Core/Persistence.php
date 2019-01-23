<?php

namespace Nap;

abstract class Persistence {

    const CRITERIA_EQUAL = '=';

    protected static $database;
    protected $dataset;
    
    public static function setDb(array &$db): string {
        return __CLASS__;
    }

    public function __construct(string $datasetName) {
        
    }

    abstract public function create(array $item): bool;

    abstract public function read(array $criteria, array $options = []): array;
    
    public function readOne(array $criteria, array $options = []): array{
        $options['limit'] = 1;
        $result = $this->read($criteria, $options);
        
        if(count($result) == 1)
            $result = $result[0];
        
        return $result;
    }

    abstract public function update(array $criteria, array $item): bool;

    abstract public function delete(array $criteria): bool;
}
