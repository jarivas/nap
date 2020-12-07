<?php

namespace Core;

use Core\Db\Persistence as DB;

class BaseAuthentication
{
    const DATA_STORE = 'user';

    public static function isValid(array $auth): bool
    {
        if (empty($auth['token'])) {
            return false;
        }

        $criteria = ['token' => [DB::CRITERIA_AND, DB::CRITERIA_EQUAL, $auth['token']]];

        $item = DB::getPersistence()->readOne($criteria, self::DATA_STORE);
        
        $GLOBALS['user'] = $item;

        return ($item) ? true : false;
    }
}
