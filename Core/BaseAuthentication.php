<?php

namespace Core;

use Core\Db\Persistence;

class BaseAuthentication
{
    const DATA_STORE = 'user';

    public static function isValid(array $params): bool
    {
        if (empty($params['token'])) {
            return false;
        }

        $criteria = ['token' => $params['token']];

        $item = Persistence::getPersistence()->readOne($criteria, self::DATA_STORE);
        
        $GLOBALS['user'] = $item;

        return ($item) ? true : false;
    }
}