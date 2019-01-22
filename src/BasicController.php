<?php

namespace App;

use \Exception;
use Nap\Controller;
use Nap\Response;

class BasicController {
    use Controller;
    
    public static function add(array $params, string $persistence): array {
        if(self::create($params, $persistence))
            return ['success' => true];
        else
            trigger_error('error on saving');
    }

    public static function get(array $params, string $persistence): array {
        return self::read($params, $persistence);
    }

    public static function set(array $params, string $persistence): array {
        if (self::update($params, $persistence))
            return ['success' => true];
        else
            throw new Exception('invalid identifier', Response::WARNING_TYPE_NOT_FOUND);
    }

    public static function remove(array $params, string $persistence): array {
        if (self::delete($params, $persistence))
            return ['success' => true];
        else
            throw new Exception('invalid identifier', Response::WARNING_TYPE_NOT_FOUND);
    }

}
