<?php
namespace Nap;

abstract class Persistence {
    protected static $database;
    protected $dataset;

    public static function setDb(array &$db){
        return __CLASS__;
    }
    
    abstract public function create(array $item);
    
    abstract public function read(array $criteria);
    
    abstract public function update(array $criteria, array $item);
    
    abstract public function delete(array $criteria);

    public function __construct(string $datasetName) {
    }
}
