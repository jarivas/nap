<?php

namespace Core;

use Core\Db\Persistence;
use Core\Db\NoSQLEmbed;

abstract class Action
{
    const USER_STORE = 'user';
    
    
    abstract public static function process(array $params, Persistence $persistence): array;
    
    protected static function getCurrentUser(): array
    {
        return $GLOBALS['user'];
    }


    protected static function getUserId(array $params, Persistence $persistence)
    {
        $user = self::getUser($params, $persistence);
        
        if (!$user) {
            return null;
        }
        
        $key = ($persistence instanceof NoSQLEmbed) ? '_id' : 'id';
        
        return $user[$key];
    }
}
