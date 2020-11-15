<?php

namespace Core;

use Core\Db\Persistence;

class BaseAuthentication {

    const USER_STORE = 'user';

    public static function isValid(array $params, Persistence $persistence): bool {
        if (empty($params['token'])) {
            return false;
        }

        $criteria = ['token' => $params['token']];

        $item = $persistence->readOne($criteria, self::USER_STORE);

        return ($item) ? true : false;
    }

}
