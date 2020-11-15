<?php

namespace Modules;

use Core\Db\Persistence;
use Core\Db\NoSQLEmbed;

abstract class Action {

    const USER_STORE = 'user';
    
    
    abstract public static function process(array $params, Persistence $persistence): array;
    
    protected static function getUser(array $params, Persistence $persistence): ?array {
        $criteria = ['token' => $params['token']];

        return $persistence->readOne($criteria, self::USER_STORE);
    }


    protected static function getUserId(array $params, Persistence $persistence) {
        $user = self::getUser($params, $persistence);
        
        if (!$user) {
            return null;
        }
        
        $key = ($persistence instanceof NoSQLEmbed) ? '_id' : 'id';        
        
        return $user[$key];
    }
}
