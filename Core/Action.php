<?php

namespace Core;

use \Core\Db\Persistence;

abstract class Action
{
    const USER_STORE = 'user';
    
    
    abstract public static function process(array $params, Persistence $persistence): array;
    
    protected static function getCurrentUser(): ?array
    {
        return emtpy($GLOBALS['user']) ? null : $GLOBALS['user'];
    }
}
