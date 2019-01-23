<?php
namespace Nap;

trait Controller {
    protected static $datasetName = '';
    
    public static function getDatasetName(){
        return self::$datasetName;
    }
    
    public static function create(array &$params, string &$persistence): bool {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->create($params);
    }
    
    public static function read(array &$params, string &$persistence): array {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->read($params);
    }
    
    public static function readOne(array &$params, string &$persistence): array {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->readOne($params);
    }
    
    public static function update(array &$params, string &$persistence): bool {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->update($params['criteria'], $params['params']);
    }
    
    public static function delete(array &$params, string &$persistence): bool {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->delete($params);
    }
}
