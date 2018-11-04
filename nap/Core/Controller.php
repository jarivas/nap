<?php
namespace Nap;

class Controller {
    protected static $datasetName = '';
    
    public static function create(array &$params, string &$persistence) {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->create($params);
    }
    
    public static function read(array &$params, string &$persistence) {
        $dataset = new $persistence(static::$datasetName);
        $criteria = [];
        
        return $dataset->read($params);
    }
    
    public static function update(array &$params, string &$persistence) {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->update($params['criteria'], $params['params']);
    }
    
    public static function delete(array &$params, string &$persistence) {
        $dataset = new $persistence(static::$datasetName);
        
        return $dataset->delete($params);
    }
}
