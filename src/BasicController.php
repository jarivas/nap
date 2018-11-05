<?php

namespace App;

use \Exception;
use Nap\Controller;
use Nap\Response;


class BasicController extends Controller {
    
    protected static function getCriteria(array &$params, bool $error){
        $criteria = [];
        
        if(isset($params['id']))
            $criteria['_id'] = $params['id'];
        else if($error)
            throw new Exception('invalid criteria', Response::WARNING_TYPE_BAD_REQUEST);
            
        return $criteria;
    }

    public static function add(array $params, string $persistence) {
        return self::create($params, $persistence);
    }

    public static function get(array $params, string $persistence) {
        $criteria = self::getCriteria($params,false);
        
        return self::read($criteria, $persistence);
    }

    public static function set(array $params, string $persistence) {
        $params['criteria'] = self::getCriteria($params['criteria'], true);
        
        if(self::update($params, $persistence))
            return ['success' => true];
        else
            throw new Exception('invalid identifier', Response::WARNING_TYPE_NOT_FOUND);
    }

    public static function remove(array $params, string $persistence) {
        $criteria = self::getCriteria($params, true);
        
        if(self::delete($criteria, $persistence))
            return ['success' => true];
        else
            throw new Exception('invalid identifier', Response::WARNING_TYPE_NOT_FOUND);
    }
}
